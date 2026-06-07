<x-app-layout>
    <x-slot name="header">
        <h1>Profile</h1>
    </x-slot>

    <div style="max-width:640px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">

        <div class="card card-body">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card card-body">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</x-app-layout>