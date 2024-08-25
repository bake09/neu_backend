<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Events\TodoSend;

use App\Events\TodoDelete;
use App\Events\TodoToggle;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Task::with('user')->get());
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            'content' => 'required|max:255',
        ]);

        $task = Task::create([
            'content' => $request->content,
            'user_id' => $user_id,
        ]);
        
        // TodoSend::dispatch($task->load('user'));
        broadcast(new TodoSend($task->load('user')))->toOthers();

        return response()->json([
            'status' => 'Task created successfully.',
            'task' => $task->load('user')
        ], 201);
    }

    public function show(Task $task)
    {
        return $task->load('user');
    }

    public function update(Request $request, Task $task)
    {
        // return $task;
        $request->validate([
            'done' => 'required|boolean',
        ]);
    
        $task->update([
            'done' => $request->done,
        ]);
    
        return $task->load('user');
        // return response()->json([
        //     'status' => 'Task updated successfully.',
        //     'task' => $task
        // ], 200);
    }
    
    public function toggledone(Request $request, Task $task)
    {
        // return $task;
        $request->validate([
            'done' => 'required|boolean',
        ]);
    
        $task->update([
            'done' => $request->done,
        ]);
        
        TodoToggle::dispatch($task->load('user'));

        return $task->load('user');
        // return response()->json([
        //     'status' => 'Task updated successfully.',
        //     'task' => $task
        // ], 200);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        broadcast(new TodoDelete($task->load('user')))->toOthers();

        return response()->json([
            'status' => 'Task deleted successfully.',
            'task' => $task
        ], 201);
    }
}
