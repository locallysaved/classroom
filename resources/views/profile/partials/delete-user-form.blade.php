<section>
    <header style="margin-bottom:20px;">
        <h2 style="font-size:15px;font-weight:700;color:var(--text);">Delete Account</h2>
        <p style="font-size:13px;color:var(--muted);margin-top:4px;">
            Once your account is deleted, all of its resources and data will be permanently deleted.
            Please download any data you wish to retain before proceeding.
        </p>
    </header>

    <button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="btn btn-primary">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}"
              style="padding:28px;display:flex;flex-direction:column;gap:20px;">
            @csrf
            @method('delete')

            <div>
                <h2 style="font-size:16px;font-weight:700;color:var(--text);">
                    Are you sure you want to delete your account?
                </h2>
                <p style="font-size:13px;color:var(--muted);margin-top:6px;line-height:1.6;">
                    Once deleted, all resources and data will be permanently removed.
                    Please enter your password to confirm.
                </p>
            </div>

            <div>
                <label for="delete_password" class="form-label">Password</label>
                <input id="delete_password" name="password" type="password"
                       class="form-input"
                       placeholder="Enter your password"
                       style="max-width:340px;">
                @error('password', 'userDeletion')
                    <p style="font-size:12px;color:var(--red);margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="btn btn-ghost"
                        style="border:1.5px solid var(--border);">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
