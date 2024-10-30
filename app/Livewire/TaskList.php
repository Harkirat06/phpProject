<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class TaskList extends Component
{
    public $user;
    public $tasks;
    public $task;
    public $button = false;
    public $sharing = false;

    public $title;
    public $description;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadTasks();
    }

    public function loadTasks(){
        $normalTasks = $this->user->tasks;
        $sharedTasks = $this->user->sharedTasks;
        $this->tasks = $normalTasks->concat($sharedTasks);
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
        $task->delete();
        $this->loadTasks();
    }

    public function editTaskModal(Task $task){
        $this->title = $task->title;
        $this->description = $task->description;
        $this->task = $task;
        $this->button = true;
        $this->dispatch('open-modal');
    }

    public function shareTask(Task $task){
        $task->sharedWith()->attach(1, ['permissions' => 'view']);
        $this->loadTasks();
    }

    public function unShareTask(Task $task){
        $task->sharedWith()->detach(1);
        $this->loadTasks();
    }
    
    public function render()
    {
        return view('livewire.task-list');
    }
}
