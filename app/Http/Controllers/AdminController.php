<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,teacher,student',
        ]);

        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return response()->json(['error' => 'You cannot remove your own admin role.'], 403);
        }

        $oldRole = $user->role;
        $user->role = $request->role;
        $user->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'event'   => 'role_changed',
            'meta'    => [
                'target_user'  => $user->name,
                'target_id'    => $user->id,
                'old_role'     => $oldRole,
                'new_role'     => $request->role,
            ],
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete your own account from here.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    public function activity()
    {
        // Standalone events
        $standalone = ActivityLog::with('user')
            ->whereIn('event', ['user_registered', 'role_changed'])
            ->orderBy('id', 'desc')
            ->get();

        // Class tiles with sub-events
        $classes = Classes::with(['teacher'])
            ->withCount([
                'activityLogs as sub_event_count' => fn($q) =>
                    $q->whereIn('event', ['task_created', 'student_joined', 'file_uploaded'])
            ])
            ->whereHas('activityLogs', fn($q) =>
                $q->where('event', 'class_created')
            )
            ->orWhereHas('activityLogs', fn($q) =>
                $q->whereIn('event', ['task_created', 'student_joined', 'file_uploaded'])
            )
            ->orderBy('id', 'desc')
            ->get();

        // Also grab class_created logs for classes that may not have sub-events
        $classCreatedLogs = ActivityLog::with(['user', 'class.teacher'])
            ->where('event', 'class_created')
            ->orderBy('id', 'desc')
            ->get()
            ->keyBy('class_id');

        // Sub-events grouped by class_id
        $subEvents = ActivityLog::with('user')
            ->whereIn('event', ['task_created', 'student_joined', 'file_uploaded'])
            ->whereNotNull('class_id')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('class_id');

        // All classes that appear in logs
        $classIds = $classCreatedLogs->keys()
            ->merge($subEvents->keys())
            ->unique();

        $allClasses = Classes::with('teacher')
            ->whereIn('id', $classIds)
            ->orderBy('id', 'desc')
            ->get()
            ->keyBy('id');

        return view('admin.activity', compact(
            'standalone', 'classCreatedLogs', 'subEvents', 'classIds', 'allClasses'
        ));
    }
}