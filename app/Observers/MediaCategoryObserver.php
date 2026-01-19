<?php

namespace App\Observers;

use App\Models\MediaCategory;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MediaCategoryObserver
{
    // Evento disparado APÓS salvar a categoria
    public function saved(MediaCategory $category)
    {
        // Verifica se é um Tour 360 e se o arquivo ZIP mudou (para não extrair a cada update de nome)
        if ($category->type === '360' && $category->custom_path && $category->wasChanged('custom_path')) {
            $this->processZip($category);
        }
    }

    private function processZip($category)
    {
        $disk = Storage::disk('public');
        $zipPath = $disk->path($category->custom_path); // Caminho do arquivo .zip
        
        // Define onde vai extrair: storage/app/public/tours/{ID}
        $extractRelativePath = 'tours/' . $category->id;
        $extractAbsolutePath = $disk->path($extractRelativePath);
        
        // 1. Cria pasta temporária (Atomicidade)
        $tempPath = $extractAbsolutePath . '_temp'; 
        
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            
            // Limpa temp se existir de uma tentativa falha anterior
            if (is_dir($tempPath)) {
                $this->deleteDirectory($tempPath);
            }
            mkdir($tempPath, 0755, true);

            // Extrai o conteúdo
            $zip->extractTo($tempPath);
            $zip->close();

            // 2. SWAP: Troca a pasta antiga pela nova instantaneamente
            if (is_dir($extractAbsolutePath)) {
                $this->deleteDirectory($extractAbsolutePath);
            }
            
            rename($tempPath, $extractAbsolutePath);
        }
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
        return rmdir($dir);
    }
}