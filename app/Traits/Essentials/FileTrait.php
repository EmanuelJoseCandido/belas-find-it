<?php

namespace App\Traits\Essentials;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{

    /**
     * Cria um arquivo em um directório especifico.
     *
     * @param object $file
     * @param string $path
     * @return null|string
     */
    public function saveFile(object $file, string $path, int $key = 0)
    {
        if (($file->isValid())) {
            $ficheiro = date('YmdHis') . $key . '.' . $file->getClientOriginalExtension();
            $newPath = config('app.api') . "/storage/" . $file->storeAs("public/$path", $ficheiro);
            return str_replace("public/", "", $newPath);
        }

        return;
    }

    /**
     * Actualiza um arquivo em um directório especifico.
     *
     * @param string $pathOldFile
     * @param object $file
     * @param string $path
     * @return string
     */
    public function updateFile(string $pathOldFile, object $file, string $path, int $key = 0): string
    {
        $this->destroyFile($pathOldFile);
        return $this->saveFile($file, $path, $key);
    }

    /**
     * Remove um arquivo em um directório especifico.
     *
     * @param string $file
     * @return bool
     */
    public function destroyFile(string $file): bool
    {
        $file = str_replace(config('app.api') . "/storage", "public", $file);

        if (Storage::exists($file)) {
            return Storage::delete($file);
        }

        return false;
    }

    /**
     * Remove uma directorio especifico.
     *
     * @param object $file
     * @return bool
     */
    public function destroyPath(string $path): bool
    {
        if (Storage::exists($path)) {
            return Storage::deleteDirectory($path);
        }
    }
}
