<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\TaskFile;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskFileController extends Controller
{
    public function store(Request $request, Tasks $tasks)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
        ]);

        $user = auth()->user();
        $visibility = ($user->isTeacher() || $user->isAdmin()) ? 'public' : 'student';

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('task_files', $filename, 'local');

        TaskFile::create([
            'task_id'       => $tasks->id,
            'user_id'       => $user->id,
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'visibility'    => $visibility,
        ]);

        ActivityLog::create([
            'user_id'  => $user->id,
            'class_id' => $tasks->class_id,
            'event'    => 'file_uploaded',
            'meta'     => [
                'file_name'  => $file->getClientOriginalName(),
                'task_name'  => $tasks->name,
                'visibility' => $visibility,
            ],
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function download(TaskFile $taskFile)
    {
        $user = auth()->user();

        if ($user->isStudent() && $taskFile->visibility === 'student' && $taskFile->user_id !== $user->id) {
            abort(403);
        }

        if (!Storage::disk('local')->exists('task_files/' . $taskFile->filename)) {
            abort(404);
        }

        return Storage::disk('local')->download('task_files/' . $taskFile->filename, $taskFile->original_name);
    }

    public function destroy(TaskFile $taskFile)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $taskFile->user_id !== $user->id) {
            abort(403);
        }

        Storage::disk('local')->delete('task_files/' . $taskFile->filename);
        $taskFile->delete();

        return back()->with('success', 'File deleted.');
    }
}