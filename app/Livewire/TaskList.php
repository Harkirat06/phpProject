<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class TaskList extends Component
{
    public $user;
    public $tasks;
    public $task;
    public $button = false;

    public $title;
    public $description;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadTasks();
    }

    public function loadTasks(){
        $this->tasks = $this->user->tasks;
    }

    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->button = false;
    }

    public function newTaskModal()
    {
        $this->resetForm();
        $this->task = new Task();
        $this->dispatch('open-modal');
    }
    
    public function saveTask()
    {
        Task::updateOrCreate([
            'id' => $this->task->id,
        ],[
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user->id,
        ]);

        // Cargamos las tareas actualizadas y limpiamos el formulario
        $this->loadTasks();
        $this->resetForm();
    }

    public function removeTask(Task $task){  
        DB::table('tasks')->where('id', '=', $task->id)->delete();
        $this->loadTasks();
    }

    public function editTaskModal(Task $task){
        $this->title = $task->title;
        $this->description = $task->description;
        $this->task = $task;
        $this->button = true;
        $this->dispatch('open-modal');
    }
    
    public function render()
    {
        return view('livewire.task-list');
    }
}
