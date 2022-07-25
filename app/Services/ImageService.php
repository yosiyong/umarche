<?php

namespace App\Services;
Use InterventionImage;
Use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        //--//リサイズして保存
        //アップロードしたファイル
        $fileName = uniqid(rand().'_');
        $extension = $imageFile->extension();
        $fileNameToStore = $fileName. '.' . $extension;

        //リサイズ
        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
        //保存
        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);

        //保存ファイル名を返す
        return $fileNameToStore;
    }
}
