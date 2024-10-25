<div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Nueva Tarea</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">Titulo</label>
                    <input  wire:model="title" type="text" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Descripción</label>
                    <textarea wire:model="description" class="form-control" id="message-text"></textarea>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button wire:click="saveTask" type="button" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
            </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto mt-6">
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
                        <button wire:click="removeTask({{ $task }})" type="button" class="btn btn-danger">Eliminar Tarea</button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
