<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = Classes::withCount('tasks')->get();
        return view('classes.index', compact('classes'));
    }

    public function show(Classes $class)
    {
        $class->load('tasks');
        return view('classes.show', compact('class'));
    }
}