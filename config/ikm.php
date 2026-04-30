<?php

// config for Bale/Ikm
return [
    'per_page' => 20,

    // Batas nilai kategori IKM (PermenPAN-RB 14/2017, bisa di-override via settings)
    'kategori' => [
        'A' => ['label' => 'Sangat Baik', 'min' => 88.31, 'max' => 100.00],
        'B' => ['label' => 'Baik', 'min' => 76.61, 'max' => 88.30],
        'C' => ['label' => 'Kurang Baik', 'min' => 65.00, 'max' => 76.60],
        'D' => ['label' => 'Tidak Baik', 'min' => 0, 'max' => 64.99],
    ],

    // 9 unsur penilaian PermenPAN-RB 14/2017
    'unsur' => [
        'u1' => 'Persyaratan',
        'u2' => 'Sistem, Mekanisme, dan Prosedur',
        'u3' => 'Waktu Penyelesaian',
        'u4' => 'Biaya/Tarif',
        'u5' => 'Produk Spesifikasi Jenis Pelayanan',
        'u6' => 'Kompetensi Pelaksana',
        'u7' => 'Perilaku Pelaksana',
        'u8' => 'Penanganan Pengaduan, Saran, dan Masukan',
        'u9' => 'Sarana dan Prasarana',
    ],

    // Bobot tertimbang per unsur (9 unsur × 0.111 = 0.999 ≈ 1)
    'bobot' => 0.111,
];
