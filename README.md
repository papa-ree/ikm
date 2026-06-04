# IKM Package for Balé

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bale/ikm.svg?style=flat-square)](https://packagist.org/packages/bale/ikm)
[![Total Downloads](https://img.shields.io/packagist/dt/bale/ikm.svg?style=flat-square)](https://packagist.org/packages/bale/ikm)

Paket **IKM (Indeks Kepuasan Masyarakat)** untuk ekosistem Balé. Paket ini menyediakan fungsionalitas lengkap untuk mengelola, menghitung, dan menampilkan data survei kepuasan masyarakat secara sistematis.

## Fitur Utama

- 📊 **Dashboard IKM**: Visualisasi data indeks secara real-time.
- 📥 **Sistem Import**: Upload data dari Excel dengan validasi otomatis.
- ⚙️ **Pengaturan Fleksibel**: Konfigurasi parameter penilaian IKM sesuai standar.
- 👥 **Manajemen Akses**: Role & Permissions terintegrasi (Admin Pusat & Admin OPD).
- 🧩 **Komponen Livewire**: UI interaktif siap pakai untuk Dashboard, List, Detail, dan Settings.

## Instalasi

1. Install paket melalui composer:
   ```bash
   composer require bale/ikm
   ```

2. Jalankan perintah instalasi interaktif:
   ```bash
   php artisan ikm:install
   ```
   *Perintah ini akan memandu Anda untuk menjalankan migrasi, membuat role & permission, serta melakukan seeding pengaturan awal.*

## Perintah Artisan (Commands)

Paket ini menyediakan beberapa perintah khusus untuk memudahkan pengelolaan:

| Command | Deskripsi |
| :--- | :--- |
| `ikm:install` | Installer interaktif (Setup migrasi, role, permission, dan seed data). |
| `ikm:publish` | Menyalin file migrasi dari paket ke folder `database/migrations/ikm` aplikasi Anda. |
| `ikm:migrate` | Menjalankan proses migrasi database khusus untuk tabel-tabel IKM. |
| `ikm:seed` | Mengisi data default untuk pengaturan awal IKM. |

## Penggunaan

### Komponen UI (Livewire)

Anda dapat menggunakan komponen Livewire yang sudah tersedia langsung di dalam view Blade Anda:

```blade
{{-- Dashboard Utama IKM --}}
<livewire:ikm.dashboard />

{{-- Daftar Rekapitulasi IKM --}}
<livewire:ikm.ikm-list />

{{-- Pengaturan Parameter IKM --}}
<livewire:ikm.settings />
```

### Navigasi / Namespace

Komponen didaftarkan secara otomatis dengan prefix `ikm.`. Contoh alias yang tersedia:
- `ikm.dashboard`
- `ikm.overview`
- `ikm.upload`
- `ikm.settings`
- `ikm.ikm-list`
- `ikm.ikm-detail`

## Konfigurasi

Untuk mengubah konfigurasi default, publish file config:
```bash
php artisan vendor:publish --tag="ikm-config"
```

## Keamanan & Akses

Paket ini menggunakan sistem permission yang ketat:
- **`ikm-admin-pusat`**: Akses penuh ke seluruh fitur dan data.
- **`ikm-admin-opd`**: Akses terbatas sesuai dengan lingkup organisasi/OPD terkait.

## Kredit

- [Papa Ree](https://github.com/paparee)
- [All Contributors](../../contributors)

## Lisensi

The MIT License (MIT). Silakan lihat [File Lisensi](LICENSE.md) untuk informasi lebih lanjut.
