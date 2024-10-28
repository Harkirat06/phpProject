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
    }

    public function openModalWindow()
    {
        $this->resetForm();
    }
    
    public function saveTask()
    {
        // Validamos los campos si es necesario
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
    
        $task = new Task();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->user_id = $this->user->id;
        $task->save();
    
        // Cargamos las tareas actualizadas y limpiamos el formulario
        $this->loadTasks();
        $this->resetForm();
    }

    public function removeTask(Task $task){  
        DB::table('tasks')->where('id', '=', $task->id)->delete();
        $this->loadTasks();
    }
    
    public function render()
    {
        return view('livewire.task-list');
    }
}
