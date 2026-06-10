<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
    </x-slot>

    <div class="py-12 pb-24">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-700">All Users <span class="text-gray-400 font-normal text-sm ml-1">({{ $users->count() }})</span></h3>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="users-table">
                        @foreach($users as $user)
                            <tr data-user-id="{{ $user->id }}" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->avatarUrl() }}"
                                             alt="{{ $user->name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ $user->name }}
                                                @if($user->id === auth()->id())
                                                    <span class="text-xs text-indigo-400 ml-1">(you)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <select
                                        data-user-id="{{ $user->id }}"
                                        data-original-role="{{ $user->role }}"
                                        data-is-self="{{ $user->id === auth()->id() ? 'true' : 'false' }}"
                                        onchange="handleRoleChange(this)"
                                        class="role-select border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                                            {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700' : ($user->role === 'teacher' ? 'bg-blue-50 text-blue-700' : 'bg-gray-50 text-gray-700') }}">
                                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}
                                            {{ $user->id === auth()->id() ? '' : '' }}>Admin</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->id !== auth()->id())
                                        <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                class="text-xs text-red-400 hover:text-red-600 transition-colors font-medium">
                                            Delete
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Save bar --}}
    <div id="save-bar"
         class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg px-6 py-4 flex items-center justify-between transform translate-y-full transition-transform duration-300 ease-in-out z-50">
        <p class="text-sm text-gray-600">You have <span id="change-count" class="font-semibold text-indigo-600">0</span> unsaved change(s).</p>
        <div class="flex items-center gap-3">
            <button onclick="discardChanges()" class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2 rounded-lg transition-colors">
                Discard
            </button>
            <button onclick="saveChanges()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition-colors">
                Save Changes
            </button>
        </div>
    </div>

    {{-- Delete confirm modal --}}
    <div id="delete-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4">
            <h3 class="font-semibold text-gray-800 mb-2">Delete Account</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete <strong id="delete-user-name"></strong>'s account? This cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteModal()" class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2 rounded-lg">Cancel</button>
                <button onclick="confirmDelete()" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const pendingChanges = {}; // { userId: newRole }
        let deleteTargetId = null;

        const routes = {
            updateRole: (id) => `/admin/users/${id}/role`,
            destroy:    (id) => `/admin/users/${id}`,
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function handleRoleChange(select) {
            const userId = select.dataset.userId;
            const isSelf = select.dataset.isSelf === 'true';
            const original = select.dataset.originalRole;
            const newRole = select.value;

            // Block self-demotion
            if (isSelf && newRole !== 'admin') {
                alert('You cannot remove your own admin role.');
                select.value = 'admin';
                return;
            }

            if (newRole === original) {
                delete pendingChanges[userId];
            } else {
                pendingChanges[userId] = newRole;
            }

            updateSaveBar();
        }

        function updateSaveBar() {
            const count = Object.keys(pendingChanges).length;
            const bar = document.getElementById('save-bar');
            document.getElementById('change-count').textContent = count;

            if (count > 0) {
                bar.classList.remove('translate-y-full');
            } else {
                bar.classList.add('translate-y-full');
            }
        }

        async function saveChanges() {
            const entries = Object.entries(pendingChanges);
            if (!entries.length) return;

            try {
                await Promise.all(entries.map(([userId, role]) =>
                    fetch(routes.updateRole(userId), {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ role }),
                    }).then(r => {
                        if (!r.ok) throw new Error();
                        // Update original role on select so discard works correctly
                        const select = document.querySelector(`select[data-user-id="${userId}"]`);
                        if (select) {
                            select.dataset.originalRole = role;
                            updateSelectStyle(select, role);
                        }
                    })
                ));

                Object.keys(pendingChanges).forEach(k => delete pendingChanges[k]);
                updateSaveBar();
            } catch {
                alert('Something went wrong saving changes. Please try again.');
            }
        }

        function discardChanges() {
            Object.entries(pendingChanges).forEach(([userId, _]) => {
                const select = document.querySelector(`select[data-user-id="${userId}"]`);
                if (select) select.value = select.dataset.originalRole;
            });
            Object.keys(pendingChanges).forEach(k => delete pendingChanges[k]);
            updateSaveBar();
        }

        function updateSelectStyle(select, role) {
            select.className = select.className
                .replace(/bg-\S+/g, '')
                .replace(/text-\S+\s/g, ' ')
                .trim();

            if (role === 'admin')        select.classList.add('bg-purple-50', 'text-purple-700');
            else if (role === 'teacher') select.classList.add('bg-blue-50',   'text-blue-700');
            else                         select.classList.add('bg-gray-50',    'text-gray-700');
        }

        function deleteUser(userId, userName) {
            deleteTargetId = userId;
            document.getElementById('delete-user-name').textContent = userName;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteTargetId = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

        async function confirmDelete() {
            if (!deleteTargetId) return;

            try {
                const res = await fetch(routes.destroy(deleteTargetId), {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                });

                if (!res.ok) throw new Error();

                // Remove row from table
                const row = document.querySelector(`tr[data-user-id="${deleteTargetId}"]`);
                if (row) row.remove();

                // Clean up pending changes for deleted user
                delete pendingChanges[deleteTargetId];
                updateSaveBar();
                closeDeleteModal();
            } catch {
                alert('Something went wrong. Please try again.');
                closeDeleteModal();
            }
        }

        // Warn before leaving with unsaved changes
        window.addEventListener('beforeunload', (e) => {
            if (Object.keys(pendingChanges).length > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</x-app-layout>