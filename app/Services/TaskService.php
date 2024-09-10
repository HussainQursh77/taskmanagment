<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Exception;

class TaskService
{

    /**
     * Get tasks filtered by priority and status with pagination.
     * Eager load the assigned user.
     *
     * @param string|null $priority
     * @param string|null $status
     * @param int $paginationLimit
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllTasks($priority = null, $status = null, $paginationLimit = 20)
    {
        return Task::with('user')
            ->priority($priority)
            ->paginate($paginationLimit);
    }

    // Create a new task
    public function createTask(array $data)
    {
        $task = new Task();
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->priority = $data['priority'];
        $task->due_date = $data['due_date'];
        $task->status = 'pending';
        $task->assigned_to = $data['assigned_to'];
        $task->save();

        return $task;
    }

    // Update an existing task
    public function updateTask(Task $task, array $data, $userId)
    {
        if (isset($data['status'])) {
            if ($task->assigned_to != $userId) {
                throw new Exception('Only the assigned user can update the status of this task', 403);
            }
            $task->status = $data['status'];
        }

        if (isset($data['title'])) {
            $task->title = $data['title'];
        }
        if (isset($data['description'])) {
            $task->description = $data['description'];
        }
        if (isset($data['priority'])) {
            $task->priority = $data['priority'];
        }
        if (isset($data['due_date'])) {
            $task->due_date = $data['due_date'];
        }

        $task->save();
        return $task;
    }

    // Delete a task
    public function deleteTask(Task $task)
    {
        $task->delete();
    }
}
