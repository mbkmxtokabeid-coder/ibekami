<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class UserList extends Component
{
    use WithPagination;

    // ── Table state ──────────────────────────────────────────────
    public int    $perPage   = 10;
    public string $search    = '';
    public string $sortField = 'id';
    public string $sortDir   = 'asc';

    // ── Modal state ──────────────────────────────────────────────
    public bool  $showModal = false;
    public bool  $isEditing = false;
    public ?int  $editingId = null;

    // ── Form fields ──────────────────────────────────────────────
    public string $username = '';
    public string $name     = '';
    public string $password = '';

    // ── Watchers ─────────────────────────────────────────────────
    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

    public function sort(string $field): void
    {
        $this->sortDir   = ($this->sortField === $field && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
        $this->resetPage();
    }

    // ── Modal helpers ─────────────────────────────────────────────
    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user            = User::findOrFail($id);
        $this->editingId = $id;
        $this->username  = $user->username;
        $this->name      = $user->name;
        $this->password  = '';          // never pre-fill password
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->username  = '';
        $this->name      = '';
        $this->password  = '';
        $this->editingId = null;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        $uniqueUsername = $this->isEditing
            ? 'unique:users,username,' . $this->editingId
            : 'unique:users,username';

        $passwordRule = $this->isEditing
            ? ['nullable', 'string', 'min:8', 'max:30']
            : ['required', 'string', 'min:8', 'max:30'];

        return [
            'username' => ['required', 'string', 'max:50', $uniqueUsername],
            'name'     => ['required', 'string', 'max:100'],
            'password' => $passwordRule,
        ];
    }

    protected function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'username.max'      => 'Username maksimal 50 karakter.',
            'name.required'     => 'Nama wajib diisi.',
            'name.max'          => 'Nama maksimal 100 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.max'      => 'Password maksimal 30 karakter.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $data = [
                'username' => $this->username,
                'name'     => $this->name,
            ];
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            User::findOrFail($this->editingId)->update($data);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Data user berhasil diperbarui.']);
        } else {
            User::create([
                'username' => $this->username,
                'name'     => $this->name,
                'password' => Hash::make($this->password),
            ]);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Admin baru berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        // Prevent deleting own account
        if (Auth::id() === $id) {
            $this->dispatch('swal', [
                'type'  => 'error',
                'title' => 'Tidak Diizinkan',
                'text'  => 'Kamu tidak bisa menghapus akun yang sedang digunakan.',
            ]);
            return;
        }

        User::findOrFail($id)->delete();
        $this->dispatch('swal', ['type' => 'success', 'title' => 'Dihapus!', 'text' => 'User berhasil dihapus.']);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) =>
                $q->where('username', 'like', "%{$this->search}%")
                  ->orWhere('name', 'like', "%{$this->search}%")
            )
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.user-list', [
            'users' => $users,
        ]);
    }
}
