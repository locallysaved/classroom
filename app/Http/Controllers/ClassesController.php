<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $classes = match(true) {
            $user->isAdmin()   => Classes::withCount('tasks')->with('teacher')->get(),
            $user->isTeacher() => Classes::withCount('tasks')->with('teacher')->where('user_id', $user->id)->get(),
            default            => $user->enrolledClasses()->withCount('tasks')->with('teacher')->get(),
        };

        return view('classes.index', compact('classes'));
    }

    public function show(Classes $class)
    {
        $user = auth()->user();

        if ($user->isTeacher() && $class->user_id !== $user->id) {
            abort(403);
        }

        if ($user->isStudent() && !$class->students()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $class->load(['tasks' => fn($q) => $q->withCount('files'), 'teacher', 'students']);
        return view('classes.show', compact('class'));
        
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Classes::create($validated);

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

}