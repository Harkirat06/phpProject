<?php

namespace App\Livewire;

use App\Models\SharedWith;
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

    public $shareWith = [];
    public $selectedUserId;
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

    public function addShareWithUser()
    {
        foreach ($this->shareWith as $existingShare) {
            if ($existingShare['userId'] == $this->selectedUserId) {
                return;
            }
        }
    
        $this->shareWith[] = [
            'userId' => $this->selectedUserId,
            'permissions' => $this->selectedPermission,
        ];
    }

    public function shareTask(){
        foreach ($this->shareWith as $shareWithElement) {
            $user = User::find($shareWithElement['userId']);
            if ($user) {
                $this->task->sharedWith()->attach($user->id, ['permissions' => $shareWithElement['permissions']]);
            }
        }

        $this->loadTasks();
        $this->resetShareForm();
    }

    
    public function resetShareForm()
    {
        $this->shareWith = [];
    }

    public function unShareTask(Task $task){
        $sharedWithIds = $task->sharedWith()->get()->map(function(User $user){
                            return $user->id;
                        }
        );

        if($sharedWithIds->contains($this->user->id)){
            $task->sharedWith()->detach($this->user->id);
        }else{
            $task->sharedWith()->detach($sharedWithIds);
        } 
    }
    
    public function render()
    {
        return view('livewire.task-list');
    }
}
