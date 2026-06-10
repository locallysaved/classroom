<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Classes;
use App\Models\TaskFile;
use App\Models\TaskSolution;
use App\Models\TaskComment;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TasksController extends Controller
{
    public function create(Request $request)
    {
        $class = Classes::findOrFail($request->query('class'));
        return view('tasks.create', compact('class'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'  => 'required|exists:classes,id',
            'name'      => 'required|string|max:255',
            'content'   => 'required|string',
            'due_date'  => 'nullable|date|after_or_equal:today',
            'files.*'   => 'nullable|file|max:20480',
        ]);

        $task = Tasks::create([
            'class_id'   => $request->class_id,
            'name'       => $request->name,
            'content'    => $request->content,
            'due_date'   => $request->due_date,
            'date_added' => now(),
        ]);

        ActivityLog::create([
            'user_id'  => auth()->id(),
            'class_id' => $task->class_id,
            'event'    => 'task_created',
            'meta'     => ['task_name' => $task->name],
        ]);

        foreach ($request->file('files', []) as $file) {
            $filename     = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $destination  = storage_path('app/task-files');
            $originalName = $file->getClientOriginalName();
            $mimeType     = $file->getMimeType(); // grab before moving
            if (!file_exists($destination)) mkdir($destination, 0755, true);
            $file->move($destination, $filename);

            TaskFile::create([
                'task_id'       => $task->id,
                'user_id'       => auth()->id(),
                'original_name' => $originalName,
                'filename'   => 'task-files/' . $filename,
                'mime_type'     => $mimeType,
                'visibility'    => 'public',
            ]);

            ActivityLog::create([
                'user_id'  => auth()->id(),
                'class_id' => $task->class_id,
                'event'    => 'file_uploaded',
                'meta'     => [
                    'file_name' => $file->getClientOriginalName(),
                    'task_name' => $task->name,
                ],
            ]);
        }

        return redirect()->route('classes.show', $task->class_id)->with('success', 'Task added!');
    }

    public function show(Tasks $task)
    {
        $task->load('files', 'comments.user', 'class');

        $mySolutions = $task->solutions()->where('user_id', auth()->id())->get();

        // Teachers and admins see all solutions
        $allSolutions = (auth()->user()->isTeacher() || auth()->user()->isAdmin())
            ? $task->solutions()->with('user')->get()
            : collect();

        return view('tasks.show', compact('task', 'mySolutions', 'allSolutions'));
    }

    public function submitSolution(Request $request, Tasks $task)
    {
        $request->validate(['solution_files.*' => 'required|file|max:20480']);

        foreach ($request->file('solution_files', []) as $file) {
            $filename    = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $destination = storage_path('app/solutions');
            if (!file_exists($destination)) mkdir($destination, 0755, true);
            $file->move($destination, $filename);

            TaskSolution::create([
                'task_id'       => $task->id,
                'user_id'       => auth()->id(),
                'original_name' => $file->getClientOriginalName(),
                'filename'   => 'solutions/' . $filename,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
            ]);

            ActivityLog::create([
                'user_id'  => auth()->id(),
                'class_id' => $task->class_id,
                'event'    => 'file_uploaded',
                'meta'     => [
                    'file_name'  => $file->getClientOriginalName(),
                    'task_name'  => $task->name,
                    'visibility' => 'student',
                ],
            ]);
        }

        return back()->with('success', 'Files submitted!');
    }

    public function storeComment(Request $request, Tasks $task)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);

        return back();
    }

    public function downloadFile(TaskFile $file)
    {
        $class = $file->task->class;

        abort_unless(
            auth()->user()->isAdmin() ||
            $class->user_id === auth()->id() ||
            $class->students->contains(auth()->id()),
            403
        );

        return response()->download(
            storage_path('app/' . $file->filename),
            $file->original_name
        );
    }

    public function downloadSolution(TaskSolution $solution)
    {
        abort_unless(
            $solution->user_id === auth()->id() ||
            auth()->user()->isAdmin() ||
            $solution->task->class->user_id === auth()->id(),
            403
        );

        return response()->download(
            storage_path('app/' . $solution->filename),
            $solution->original_name
        );
    }

    public function destroy(Tasks $task)
    {
        abort_unless(
            auth()->user()->isAdmin() || $task->class->user_id === auth()->id(),
            403
        );

        $classId = $task->class_id;
        $task->delete();

        return redirect()->route('classes.show', $classId)->with('success', 'Task deleted.');
    }
}