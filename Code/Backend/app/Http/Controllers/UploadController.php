<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Helpers\UploadHelper;
use Services\ImageService;
use Services\MasterImageService;
use Validator;

class UploadController extends Controller {
    public $imageService;
    public $masterImageService;
    public function __construct(ImageService $imageService, MasterImageService $masterImageService) {
        $this->imageService = $imageService;
        $this->masterImageService = $masterImageService;
    }

    public function uploadImage(Request $request) {
        $validator = Validator::make($request->all(), [
                    'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->all()], 400);
        }
        
        $url=UploadHelper::uploadFile($request, 'file');

        
        if($url != null){
            return response()->json(["url" => $url], 200);

        }
        return response()->json(["error" => "The file can't be uploaded"], 400);
        
    }

}
