<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users with roles can view tasks
        return in_array($user->role, ['admin', 'doctor', 'nurse']);
    }

    /**
     * Determine whether the user can view a specific task.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->role === 'admin' ||
               $user->id === $task->doctor_id ||
               $user->id === $task->nurse_id;
    }

    /**
     * Determine whether the user can create a task.
     */
    public function create(User $user): bool
    {
        // Only admin or doctor can create tasks
        return in_array($user->role, ['admin', 'doctor']);
    }

    /**
     * Determine whether the user can update a task.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->role === 'admin' ||
               $user->id === $task->doctor_id;
    }

    /**
     * Determine whether the user can delete a task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can mark a task as completed or perform custom actions.
     */
    public function markComplete(User $user, Task $task): bool
    {
        return $user->role === 'nurse' && $user->id === $task->nurse_id;
    }
}
