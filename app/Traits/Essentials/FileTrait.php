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

            // Armazenar no disco 'public'
            $storagePath = $file->storeAs($path, $ficheiro, 'public');

            // Retorna a URL pública completa
            return asset('storage/' . $storagePath);
        }
        return null;
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
        // Extrair o caminho relativo da URL completa
        $url = parse_url($file, PHP_URL_PATH);
        $relativePath = str_replace('/storage/', '', $url);

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->delete($relativePath);
        }
        return false;
    }

    /**
     * Remove uma directorio especifico.
     *
     * @param string $path
     */
    public function destroyPath(string $path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->deleteDirectory($path);
        }
    }
}
