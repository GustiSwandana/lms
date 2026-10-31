<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

$users = User::with('school')->get();

echo 'Total users found: '.$users->count().PHP_EOL;

$exportDir = __DIR__.'/../exports';
if (! is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

$publicExportDir = __DIR__.'/../public/exports';
if (! is_dir($publicExportDir)) {
    mkdir($publicExportDir, 0755, true);
}

$csvPath = $exportDir.'/data_pengguna_siagabumi_ssk.csv';
$publicCsvPath = $publicExportDir.'/data_pengguna_siagabumi_ssk.csv';
$jsonPath = $exportDir.'/data_pengguna_siagabumi_ssk.json';
$publicJsonPath = $publicExportDir.'/data_pengguna_siagabumi_ssk.json';

// 1. Export to CSV
$fp = fopen($csvPath, 'w');
// UTF-8 BOM for Excel compatibility
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($fp, [
    'ID',
    'Nama Lengkap',
    'Email',
    'Peran (Role)',
    'Label Peran',
    'Status Akun',
    'Satuan Sekolah',
    'NPSN Sekolah',
    'NISN',
    'Nomor Telepon/WA',
    'Alamat Domisili',
    'Waktu Pendaftaran',
    'Pembaruan Terakhir',
]);

$userData = [];

foreach ($users as $user) {
    $row = [
        $user->id,
        $user->name,
        $user->email,
        $user->role,
        $user->role_label,
        $user->is_active ? 'Aktif' : 'Nonaktif',
        $user->school_name ?? ($user->school?->name ?? 'Pusat BKKBN / Lintas Sekolah'),
        $user->school?->npsn ?? '-',
        $user->nisn ?? '-',
        $user->phone ?? '-',
        $user->address ?? '-',
        $user->created_at?->format('Y-m-d H:i:s') ?? '-',
        $user->updated_at?->format('Y-m-d H:i:s') ?? '-',
    ];
    fputcsv($fp, $row);

    $userData[] = [
        'id' => $user->id,
        'nama_lengkap' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'role_label' => $user->role_label,
        'status' => $user->is_active ? 'Aktif' : 'Nonaktif',
        'sekolah' => $user->school_name ?? ($user->school?->name ?? 'Pusat BKKBN / Lintas Sekolah'),
        'npsn' => $user->school?->npsn ?? null,
        'nisn' => $user->nisn ?? null,
        'telepon' => $user->phone ?? null,
        'alamat' => $user->address ?? null,
        'created_at' => $user->created_at?->toIso8601String(),
        'updated_at' => $user->updated_at?->toIso8601String(),
    ];
}

fclose($fp);
copy($csvPath, $publicCsvPath);

// 2. Export to JSON
file_put_contents($jsonPath, json_encode($userData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
copy($jsonPath, $publicJsonPath);

// 3. Copy to Artifact Directory
$artifactDir = 'C:/Users/gusti/.gemini/antigravity/brain/86519e43-2ec3-4137-97ab-bd5701e36cce';
copy($csvPath, $artifactDir.'/data_pengguna_siagabumi_ssk.csv');
copy($jsonPath, $artifactDir.'/data_pengguna_siagabumi_ssk.json');

echo 'CSV exported to: '.$csvPath.' ('.filesize($csvPath).' bytes)'.PHP_EOL;
echo 'JSON exported to: '.$jsonPath.' ('.filesize($jsonPath).' bytes)'.PHP_EOL;
