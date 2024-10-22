<div>
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
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
