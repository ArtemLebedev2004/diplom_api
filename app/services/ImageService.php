<?php

namespace App\Services;

use Intervention\Image\Facades\Image as ImageIntervention;

class ImageService
{
    public function updateImage($model, $request, $path, $methodType) {
        $image = ImageIntervention::make($request->file('image'));

        if(!empty($model->image)) {
            $current_image = public_path() . $path . $model->image;

            if (file_exists($current_image)) {
                unlink($current_image);
            }
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        $image->crop(
            $request->width,
            $request->height,
            $request->left,
            $request->top
        );

        $name = uniqid() . '.' . $extension;
        $image->save(public_path() . $path . $name);

        // if ($methodType === 'store') {
        //     $model->user_id = $request->get('user_id');
        // }

        $model->image = $name;

        $model->save();
    }
}
