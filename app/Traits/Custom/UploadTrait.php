<?php

namespace App\Traits\Custom;

use App\Enums\Essentials\FilePaths;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

trait UploadTrait
{

    /**
     * upload specified construction image
     *
     * @param $id
     * @param $imageRequest
     * @return mixed
     */
    public function uploadConstructionImage($id, $imageRequest): mixed
    {
        return $imageRequest->file('image')->store(FilePaths::CONSTRUCTION->value.'/'.$id) ?? throw new UploadException();
    }

    /**
     * upload employees profile pics
     *
     * @param $id
     * @param $imageRequest
     * @return mixed
     */
    public function uploadProfileImage($id, $imageRequest): mixed
    {
        return $imageRequest->file('image')->store(FilePaths::PROFILE->value.'/'.$id) ?? throw new UploadException();
    }

    /**
     * upload employees cv's
     *
     * @param $id
     * @param $fileRequest
     * @return mixed
     */
    public function uploadCv($id, $fileRequest): mixed
    {
        return $fileRequest->file('doc')->store(FilePaths::CV->value.'/'.$id) ?? throw new UploadException();
    }

    /**
     * upload employees justified faults docs
     *
     * @param $id
     * @param $fileRequest
     * @return mixed
     */
    public function uploadFaultsDocs($id, $fileRequest): mixed
    {
        return $fileRequest->file('doc')->store(FilePaths::FAULT_DOCS->value.'/'.$id) ?? throw new UploadException();
    }
}
