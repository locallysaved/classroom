<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Classes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($classes->isEmpty())
                <div class="bg-white rounded-xl shadow p-10 text-center text-gray-400">
                    <p class="text-lg">No classes yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classes as $class)
                        <a href="{{ route('classes.show', $class) }}"
                           class="block bg-white rounded-xl shadow hover:shadow-md transition-shadow duration-200 p-6 group">
                            <h3 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors duration-200">
                                {{ $class->name }}
                            </h3>
                            @if($class->description)
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $class->description }}</p>
                            @endif
                            <div class="mt-4 flex items-center gap-1 text-xs text-indigo-500 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                {{ $class->tasks_count }} {{ Str::plural('task', $class->tasks_count) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>