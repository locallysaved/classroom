<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Join a Class</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-8">

                <p class="text-sm text-gray-500 mb-6">Enter the class code given to you by your teacher.</p>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('classes.join') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="join_code" class="block text-sm font-medium text-gray-700 mb-1">Class Code</label>
                        <input type="text" name="join_code" id="join_code"
                               value="{{ $prefill ?? old('join_code') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono tracking-widest uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="e.g. AB12CD34"
                               maxlength="8"
                               required>
                    </div>
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors duration-200">
                        Join Class
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>