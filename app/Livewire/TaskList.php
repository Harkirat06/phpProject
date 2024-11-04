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

    public $selectedUser;
    public $selectedPermission = 'view';

    public $title;
    public $description;
    public $users;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadTasks();
        $this->users = $this->getUsersToShare();
    }

    public function loadTasks(){
        $normalTasks = $this->user->tasks;
        $sharedTasks = $this->user->sharedTasks;
        $this->tasks = $normalTasks->concat($sharedTasks);
    }
    public function getUsersToShare(){
        return User::all()->where('id', '!=', $this->user->id);
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
        if(!$this->task->sharedWith()->get()->isEmpty()){
            Task::updateOrCreate([
                'id' => $this->task->id,
            ],[
                'title' => $this->title,
                'description' => $this->description,
            ]);
        }else{
            Task::updateOrCreate([
                'id' => $this->task->id,
            ],[
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => $this->user->id,
            ]);
        }
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

    public function openShareTaskModal(Task $task){
        $this->task = $task;
        $this->dispatch('open-share');
    }

    public function shareTask()
    {
        if ($this->selectedUser && $this->selectedPermission) {
            $user = User::find($this->selectedUser);
            if ($user) {
                $this->task->sharedWith()->attach($user->id, ['permissions' => $this->selectedPermission]);
                $this->loadTasks();
                $this->resetShareForm();
            }
        }
    }
    
    public function resetShareForm()
    {
        $this->selectedUser = null;
        $this->selectedPermission = 'view';
    }

    public function unShareTask(Task $task){
        $id = $task->sharedWith()->get()->first()->id;
        $task->sharedWith()->detach($id);
    }
    
    public function render()
    {
        return view('livewire.task-list');
    }
}
