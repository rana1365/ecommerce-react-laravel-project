<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImageController extends Controller
{
    // This method will store the temporary image

    public function store (Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        // Store the image
        $tempImage = new TempImage();
        $tempImage->name = 'Dummy name';
        
        $tempImage->save();

        $image = $request->file('image');
        $imageName = time().'.'.$image->extension();
        $image->move(public_path('uploads/temp'), $imageName);

        $tempImage->name = $imageName;
        $tempImage->save();

        // Save image as thumbnail
        $manager = new ImageManager(Driver::class);
        $img = $manager->read(public_path('uploads/temp/' .$imageName));
        $img->coverDown(400, 450);
        $img->save(public_path('uploads/temp/thumb/' .$imageName));

        return response()->json([
            'status' => 200,
            'message' => 'Image has been uploaded successfully',
            'data' => $tempImage
        ], 200);

    }

}
