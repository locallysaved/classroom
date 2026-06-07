<x-app-layout>
    <x-slot name="header">
        <h1>Join a Class</h1>
    </x-slot>

    <div style="max-width:440px;margin:0 auto;">
        <div class="card card-body">

            <p style="font-size:14px;color:var(--muted);margin-bottom:20px;">
                Enter the class code given to you by your teacher.
            </p>

            @if($errors->any())
                <div class="alert-error" style="margin-bottom:20px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('classes.join') }}" method="POST" style="display:flex;flex-direction:column;gap:18px;">
                @csrf
                <div>
                    <label for="join_code" class="form-label">Class Code</label>
                    <input type="text" name="join_code" id="join_code"
                           value="{{ $prefill ?? old('join_code') }}"
                           class="form-input"
                           style="font-family:monospace;letter-spacing:.2em;text-transform:uppercase;
                                  font-size:20px;font-weight:700;text-align:center;"
                           placeholder="AB12CD34"
                           maxlength="8"
                           required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Join Class
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
