<?php

namespace App\Services;
Use InterventionImage;
Use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        //dd($imageFile);

        //配列の場合
        if(is_array($imageFile)){
            $file = $imageFile['image'];
        }else{
            $file = $imageFile;
        }

        //ファイル名生成
        $fileName = uniqid(rand().'_');
        $extension = $file->extension();
        $fileNameToStore = $fileName. '.' . $extension;

        //リサイズ
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
        //保存
        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);
        //Storage::putFile('public/shops', $fileNameToStore);

        //保存ファイル名を返す
        return $fileNameToStore;
    }
}
