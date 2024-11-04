<div>
    <button x-data wire:click="newTaskModal" type="button" class="btn btn-primary">Nueva Tarea</button>
    <div wire:poll.200ms="loadTasks" class="container mx-auto mt-6">
        <h2 class="text-2xl font-semibold mb-4">Tus tareas {{$user->name}}</h2>
        @if ($tasks->isEmpty())
            <p class="text-gray-400">No tienes tareas pendientes.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($tasks as $task)
                    <div class="bg-gray-800 text-white shadow-md rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-2">{{ $task->title }}</h3>
                        <p class="text-white mb-4">{{ $task->description }}</p>
                        <p class="text-sm text-gray-400">Fecha de creación: {{ $task->created_at->format('d/m/Y') }}</p>
                        
                        <div class="flex space-x-2 mt-4">
                            <!-- Botón para Editar -->
                            @if(Auth::user()->id == $task->user_id || (!$task->sharedWith()->get()->isEmpty() && $task->sharedWith()->first()->pivot->permissions == 'edit'))
                            
                            <button wire:click="editTaskModal({{ $task }})" class="px-2 py-2 bg-yellow-500 text-white rounded">Editar</button>

                            @endif
                            @if($task->sharedWith()->get()->isEmpty())
                            
                            <!-- Botón para Compartir -->
                            <button wire:click="openShareTaskModal({{ $task }})" class="px-2 py-2 bg-blue-500 text-white rounded">Compartir</button>

                            <!-- Botón para Eliminar -->
                            @if(Auth::user()->id == $task->user_id)

                            <button wire:click="removeTask({{ $task }})" class="px-2 py-2 bg-red-600 text-white rounded">Eliminar</button>

                            @endif
                            
                            @else

                            <button wire:click="unShareTask({{ $task }})" class="px-2 py-2 bg-blue-500 text-white rounded">Descompartir</button>

                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Modal para crear una nueva o modificarla tarea -->
    <div 
        x-data="{ modal : false }" 
        x-show="modal" 
        x-transition 
        x-on:open-modal.window="modal = true" 
        x-on:close-modal.window="modal = false; @this.resetForm()" 
        style="display: none;"
        class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
        
        <div @click.outside="modal = false; $dispatch('close-modal')" class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
            <div class="w-full p-6">
                <h2 class="text-2xl font-semibold mb-4">Nueva Tarea</h2>
                <div class="mb-4">
                    <label class="block text-gray-700">Título</label>
                    <input type="text" wire:model="title" class="w-full p-2 border rounded" placeholder="Título de la tarea">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Descripción</label>
                    <textarea wire:model="description" class="w-full p-2 border rounded" placeholder="Descripción de la tarea"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button @click="modal = false" wire:click="resetForm" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
        
                    <button wire:click="saveTask" @click="modal = false" class="px-4 py-2 bg-blue-600 text-white rounded">
                    {{ $button ? 'Modificar' : 'Guardar'  }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div 
    x-data="{ modalShare : false }" 
    x-show="modalShare" 
    x-transition 
    x-on:open-share.window="modalShare = true" 
    x-on:close-share.window="modalShare = false" 
    style="display: none;"
    class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">

    <div @click.outside="modalShare = false" class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
        <div class="w-full p-6">
            <h2 class="text-2xl font-semibold mb-4">Compartir Tarea</h2>
            
            <!-- Selección de Usuario -->
            <div class="mb-4">
                <label class="block text-gray-700">Usuario</label>
                <select wire:model="selectedUser" class="w-full p-2 border rounded">
                    <option value="">Selecciona un usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Selección de Permiso -->
            <div class="mb-4">
                <label class="block text-gray-700">Permiso</label>
                <select wire:model="selectedPermission" class="w-full p-2 border rounded">
                    <option value="view">Ver</option>
                    <option value="edit">Editar</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <button @click="modalShare = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <button wire:click="shareTask" @click="modalShare = false" class="px-4 py-2 bg-blue-600 text-white rounded">
                    Compartir
                </button>
            </div>
        </div>
    </div>

</div>
