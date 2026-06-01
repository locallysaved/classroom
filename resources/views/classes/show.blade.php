<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('classes.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $class->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Class description --}}
            @if($class->description)
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-600">{{ $class->description }}</p>
                </div>
            @endif

            {{-- Tasks --}}
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-700">Tasks</h3>
                    <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2.5 py-1 rounded-full">
                        {{ $class->tasks->count() }} {{ Str::plural('task', $class->tasks->count()) }}
                    </span>
                </div>

                @if($class->tasks->isEmpty())
                    <div class="p-10 text-center text-gray-400">
                        <p>No tasks for this class yet.</p>
                    </div>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach($class->tasks as $task)
                            <li class="px-6 py-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800">{{ $task->name }}</h4>
                                        <p class="mt-1 text-sm text-gray-500">{{ $task->content }}</p>
                                    </div>
                                    <span class="shrink-0 text-xs text-gray-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($task->date_added)->format('M d, Y') }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>