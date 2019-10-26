<?php

namespace Helpers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class UploadHelper
{

    public static function uploadFile(Request $request, $fileName)
    {
        if (Input::hasFile($fileName)) {
            $file = Input::file($fileName);
            $path = Input::get('path');

            $destinationPath = public_path() . "/uploads" . $path;
            $name = preg_replace('/\s+/', '', $file->getClientOriginalName());
            $filename = time() . '_' . $name;
            if ($file->move($destinationPath, $filename)) {
                $filePath = "uploads" . $path . '/' . $filename;
                return $filePath;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public static function uploadBulk($files, $subDirectory)
    {

        $filePaths = [];
        $destinationPath = public_path() . "/uploads" . $subDirectory;

        $i = 0;
        foreach ($files as $file) {
            $name = preg_replace('/\s+/', '', $file['original']->getClientOriginalName());
            $filename = time() . '_' . $name;
            if ($file['original']->move($destinationPath, $filename)) {
                $filePath = "uploads" . $subDirectory . '/' . $filename;
                $filePaths[$i]['original'] = $filePath;
            }

            if(isset($file['cropped'])){
                $name = preg_replace('/\s+/', '', $file['cropped']->getClientOriginalName());
                $filename = time() . '_' . $name;
                if ($file['cropped']->move($destinationPath, $filename)) {
                    $filePath = "uploads" . $subDirectory . '/' . $filename;
                    $filePaths[$i]['cropped'] = $filePath;
                }
            }else{
                $filePaths[$i]['cropped'] = $filePaths[$i]['original'];
            }

            $i++;
        }

        return $filePaths;
    }
}