<?php

namespace App\Http\Controllers;
use App\Models\TaskFile;
use App\Models\TaskSolution;
use App\Models\TaskComment;
use App\Models\Tasks;
use App\Models\Classes;
use Illuminate\Http\Request;

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
        'class_id' => 'required|exists:classes,id',
        'name'     => 'required|string|max:255',
        'content'  => 'required|string',
        'files.*'  => 'nullable|file|max:20480',
    ]);

    $task = Tasks::create($request->only('class_id', 'name', 'content'));

    foreach ($request->file('files', []) as $file) {
        $filename     = uniqid() . '.' . $file->getClientOriginalExtension();
        $destination  = storage_path('app\\task-files');
        $originalName = $file->getClientOriginalName();

        $file->move($destination, $filename);

        $task->files()->create([
            'original_name' => $originalName,
            'stored_name'   => 'task-files\\' . $filename,
        ]);
    }

    return redirect()->route('classes.show', $task->class_id)
                     ->with('success', 'Task added!');
}

    public function show(Tasks $task)
    {
        $task->load('files', 'comments.user', 'class');
        $mySolutions = $task->solutions()->where('user_id', auth()->id())->get();
        return view('tasks.show', compact('task', 'mySolutions'));
    }

public function submitSolution(Request $request, Tasks $task)
{
    $request->validate(['solution_files.*' => 'required|file|max:20480']);

    foreach ($request->file('solution_files', []) as $file) {
        $filename    = uniqid() . '.' . $file->getClientOriginalExtension();
        $destination = storage_path('app\\solutions');
        
        $originalName = $file->getClientOriginalName();
        
        $file->move($destination, $filename);

        $task->solutions()->create([
            'user_id'       => auth()->id(),
            'original_name' => $originalName,
            'stored_name'   => 'solutions\\' . $filename,
        ]);
    }

    return back()->with('success', 'Files submitted!');
}
    public function storeComment(Request $request, Tasks $task)
    {
        $request->validate(['body' => 'required|string|max:1000']);
        $task->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);
        return back();
    }

public function downloadFile(TaskFile $file)
{
    $class = $file->task->class;
    abort_unless(
        $class->user_id === auth()->id() || $class->students->contains(auth()->id()),
        403
    );
    return response()->download(
        storage_path('app\\' . $file->stored_name),
        $file->original_name
    );
}

public function downloadSolution(TaskSolution $solution)
{
    abort_unless(
        $solution->user_id === auth()->id() ||
        $solution->task->class->user_id === auth()->id(),
        403
    );
    return response()->download(
        storage_path('app\\' . $solution->stored_name),
        $solution->original_name
    );
}
}