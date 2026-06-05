<?php

namespace Bale\Ikm\Livewire\Setting;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Ikm\IkmPermissions;
use Illuminate\Support\Facades\Auth;

class TemplateUpload extends Component
{
    use WithFileUploads;

    public $template_file;

    /**
     * Temporary file upload disk.
     * local for dev, s3 for production.
     */
    public function temporaryFileUploadDisk(): string
    {
        return app()->isProduction() ? 's3' : 'local';
    }

    public function saveTemplate()
    {
        TenantConnectionService::ensureActive();
        abort_unless(Auth::user()?->can(IkmPermissions::MANAGE_SETTINGS), 403);

        $this->validate([
            'template_file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $disk = $this->temporaryFileUploadDisk();
        $slug = session('bale_active_slug');
        $filename = 'template_ikm_standar.xlsx'; // Fixed filename for template
        $path = $slug . '/ikm/' . $filename;

        // Store file
        Storage::disk($disk)->put($path, $this->template_file->get());

        // Update settings/config if needed, but for now just storing it in the specific path
        // We might want to store the path in a setting to be dynamic, 
        // but the user specified a fixed logic path.

        $this->dispatch('toast', message: 'Template IKM berhasil diperbarui.', type: 'success');
        $this->reset('template_file');
    }

    public function render()
    {
        return view('ikm::livewire.setting.template-upload');
    }
}
