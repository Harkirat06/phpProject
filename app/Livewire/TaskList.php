<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskList extends Component
{
    public $user;
    public $tasks;

    public function mount()
    {
        $this->user = Auth::user();
        $this->tasks = $this->user->tasks;
    }
    
    public function render()
    {
        return view('livewire.task-list');
    }
}
