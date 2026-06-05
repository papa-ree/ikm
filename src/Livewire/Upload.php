<?php

namespace Bale\Ikm\Livewire;

use Bale\Ikm\Models\IkmBatch;
use Bale\Ikm\Services\IkmImportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.app')]
#[Title('Upload IKM')]
class Upload extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:100')]
    public string $nama = '';

    #[Validate('required|integer|between:1,4')]
    public int $triwulan = 1;

    #[Validate('required|integer|min:2020|max:2099')]
    public int $tahun;

    #[Validate('required|file|mimes:xlsx,xls|max:5120')]
    public $file = null;

    public bool $uploading = false;

    public function mount(): void
    {
        TenantConnectionService::ensureActive();
        $this->tahun = (int) now()->format('Y');
        $this->triwulan = (int) ceil((int) now()->format('n') / 3);
    }

    public function updatedFile(): void
    {
        $this->validateOnly('file');
    }

    public function save(IkmImportService $importService): void
    {
        TenantConnectionService::ensureActive();
        Gate::authorize('create', IkmBatch::class);

        $this->validate();

        $this->uploading = true;

        try {
            // Simpan file ke storage/app/ikm/uploads/
            $path = $this->file->store('ikm/uploads', 'local');

            // Buat batch dengan status awal 'diproses'
            $batch = IkmBatch::create([
                'nama' => $this->nama,
                'triwulan' => $this->triwulan,
                'tahun' => $this->tahun,
                'nama_file' => $this->file->getClientOriginalName(),
                'path_file' => $path,
                'status' => 'diproses',
                'uploaded_by' => Auth::user()->uuid,
            ]);

            // Jalankan import langsung (gunakan queue di produksi via observer/event)
            $result = $importService->import($batch);

            if ($result->hasErrors()) {
                session()->flash('warning', "Import selesai dengan {$result->failed} baris gagal. Cek catatan pada batch.");
            } else {
                session()->flash('success', "Import berhasil! {$result->success} OPD berhasil diimport.");
            }

            $this->redirect(route('ikm.list'), navigate: true);

        } finally {
            $this->uploading = false;
        }
    }

    public function downloadTemplate()
    {
        TenantConnectionService::ensureActive();
        $disk = app()->isProduction() ? 's3' : 'local';
        $slug = session('bale_active_slug');
        $path = $slug . '/ikm/template_ikm_standar.xlsx';

        if (!Storage::disk($disk)->exists($path)) {
            $this->dispatch('toast', message: 'Template belum diunggah oleh admin.', type: 'warning');
            return null;
        }

        return Storage::disk($disk)->download($path, 'Template_IKM_Standar.xlsx');
    }

    public function render()
    {
        return view('ikm::livewire.upload');
    }
}
