<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $request->validate(['role' => 'required|in:student,teacher,admin']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role updated.');
    }

    public function destroy(User $user)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        abort_if($user->id === auth()->id(), 403);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function activity()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.activity', compact('logs'));
    }
}