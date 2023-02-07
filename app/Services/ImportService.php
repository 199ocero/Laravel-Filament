<?php

namespace App\Services;

use App\Imports\StudentImport;
use Illuminate\Support\Facades\Storage;

class ImportService
{
    public function import($file)
    {
        $filePath = "public/$file";
        $import = new StudentImport();

        $import->import(storage_path("app/$filePath"));

        Storage::disk('public')->delete($file);
    }
}
