<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('classes.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $class->name }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">by {{ $class->teacher->name ?? 'Unknown' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Class description --}}
            @if($class->description)
                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-600">{{ $class->description }}</p>
                </div>
            @endif

            {{-- Join Code & QR — teachers and admins only --}}
        @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
            @if($class->join_code)
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-700">Class Access Code</h3>
                            <p class="text-sm text-gray-400 mt-0.5">Share this with your students to let them join</p>
                        </div>
                        <button onclick="toggleCode()"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                            Show Code & QR
                        </button>
                    </div>

                    <div id="code-reveal" class="hidden mt-6 flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                        {{-- Join code --}}
                        <div class="flex-1">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Join Code</p>
                            <div class="flex items-center gap-3">
                                <span class="text-3xl font-mono font-bold tracking-widest text-indigo-600 bg-indigo-50 px-4 py-3 rounded-lg">
                                    {{ $class->join_code }}
                                </span>
                                <button onclick="copyCode('{{ $class->join_code }}')"
                                        class="text-gray-400 hover:text-indigo-600 transition-colors" title="Copy code">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                            <p id="copied-msg" class="hidden text-xs text-green-500 mt-2">Copied to clipboard!</p>
                        </div>

                        {{-- QR Code --}}
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">QR Code</p>
                            <div class="bg-white border border-gray-200 rounded-lg p-2 inline-block">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/join/' . $class->join_code)) }}"
"
                                     alt="QR Code for {{ $class->name }}"
                                     class="w-36 h-36 rounded">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @endif

            {{-- Tasks --}}
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-700">Tasks</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2.5 py-1 rounded-full">
                            {{ $class->tasks->count() }} {{ Str::plural('task', $class->tasks->count()) }}
                        </span>
                        @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                            <a href="{{ route('tasks.create', ['class' => $class->id]) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                                + Add Task
                            </a>
                        @endif
                    </div>
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

    <script>
        function toggleCode() {
            const el = document.getElementById('code-reveal');
            const btn = event.currentTarget;
            const hidden = el.classList.contains('hidden');
            el.classList.toggle('hidden', !hidden);
            btn.textContent = hidden ? 'Hide Code & QR' : 'Show Code & QR';
        }

        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                const msg = document.getElementById('copied-msg');
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            });
        }
    </script>
</x-app-layout>