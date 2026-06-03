<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\Classes;
use Illuminate\Http\Request;

class TasksController extends Controller{
    public function create(Request $request)
    {
        $class = Classes::findOrFail($request->query('class'));
        return view('tasks.create', compact('class'));
    }   

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name'     => 'required|string|max:255',
            'content'  => 'required|string',
        ]);

        $validated['date_added'] = now();

        Tasks::create($validated);

        return redirect()->route('classes.show', $validated['class_id'])->with('success', 'Task added successfully.');
    }
}