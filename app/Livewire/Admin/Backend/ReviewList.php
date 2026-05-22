<?php

namespace App\Livewire\Admin\Backend;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ReviewList extends Component
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
    public string $name        = '';
    public string $review_id   = '';
    public string $review_en   = '';
    public string $star        = '';
    public string $review_date = '';

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
        $review            = Review::findOrFail($id);
        $this->editingId   = $id;
        $this->name        = $review->name;
        $this->review_id   = $this->loadTextField($review, 'review_id');
        $this->review_en   = $this->loadTextField($review, 'review_en');
        $this->star        = (string) $review->star;
        $this->review_date = $review->review_date?->format('Y-m-d') ?? '';
        $this->isEditing   = true;
        $this->showModal   = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function loadTextField(Review $review, string $column): string
    {
        $attributes = $review->getAttributes();

        if (array_key_exists($column, $attributes) && $attributes[$column] !== null && $attributes[$column] !== '') {
            return (string) $attributes[$column];
        }

        $raw = $review->getRawOriginal($column);

        return is_string($raw) && $raw !== '' ? $raw : '';
    }

    private function resetForm(): void
    {
        $this->name        = '';
        $this->review_id   = '';
        $this->review_en   = '';
        $this->star        = '';
        $this->review_date = '';
        $this->editingId   = null;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'review_id'   => ['required', 'string'],
            'review_en'   => ['required', 'string'],
            'star'        => ['required', 'integer', 'min:1', 'max:5'],
            'review_date' => ['nullable', 'date'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'      => 'Nama reviewer wajib diisi.',
            'name.max'           => 'Nama maksimal 150 karakter.',
            'review_id.required' => 'Teks review (Bahasa Indonesia) wajib diisi.',
            'review_en.required' => 'Teks review (English) wajib diisi.',
            'star.required'      => 'Rating wajib dipilih.',
            'star.min'           => 'Rating minimal bintang 1.',
            'star.max'           => 'Rating maksimal bintang 5.',
            'review_date.date'   => 'Format tanggal tidak valid.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $data = [
            'name'        => $this->name,
            'review_id'   => $this->review_id,
            'review_en'   => $this->review_en,
            'star'        => (int) $this->star,
            'review_date' => $this->review_date ?: null,
        ];

        if ($this->isEditing) {
            Review::findOrFail($this->editingId)->update($data);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Review berhasil diperbarui.']);
        } else {
            Review::create($data);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Review berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        Review::findOrFail($id)->delete();
        $this->dispatch('swal', ['type' => 'success', 'title' => 'Dihapus!', 'text' => 'Review berhasil dihapus.']);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $reviews = Review::query()
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('review_id', 'like', "%{$this->search}%")
                  ->orWhere('review_en', 'like', "%{$this->search}%")
            )
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.backend.review-list', [
            'reviews' => $reviews,
        ]);
    }
}
