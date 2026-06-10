<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Classes;
use Illuminate\Http\Request;

class JoinClassController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'join_code' => 'required|string',
        ]);

        $class = Classes::where('join_code', strtoupper($request->join_code))->first();

        if (!$class) {
            return back()->withErrors(['join_code' => 'Invalid class code.'])->withInput();
        }

        $user = auth()->user();

        if ($class->students()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['join_code' => 'You are already enrolled in this class.'])->withInput();
        }

        $class->students()->attach($user->id);

        ActivityLog::create([
            'user_id'  => $user->id,
            'class_id' => $class->id,
            'event'    => 'student_joined',
            'meta'     => ['student_name' => $user->name],
        ]);

        return redirect()->route('classes.show', $class)->with('success', 'You have joined the class!');
    }
}