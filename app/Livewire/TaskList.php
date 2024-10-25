<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class TaskList extends Component
{
    public $user;
    public $tasks;

    public $title;
    public $description;

    public function mount()
    {
        $this->user = Auth::user();
        $this->tasks = $this->user->tasks;
    }

    public function saveTask(){
        $task = new Task();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->user_id = $this->user->id;
        $task->save();
    }

    public function removeTask(Task $task){  
        DB::table('tasks')->where('id', '=', $task->id)->delete();
    }
    
    public function render()
    {
        return view('livewire.task-list');
    }
}
