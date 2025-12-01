<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Rules\AllowedFileExtensions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FileController extends AdminBaseController
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload' => ['required', 'file', new AllowedFileExtensions],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'uploaded' => false,
                'error' => $validator->errors()->first(),
            ]);
        }

        $file = $request->file('upload');

        if (! $file) {
            return response()->json([
                'uploaded' => false,
                'error' => __('Unable to upload file'),
            ], Response::HTTP_OK);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs(CommonHelper::getEditorPath(), $fileName, 'public');

        return response()->json([
            'uploaded' => true,
            'url' => url(CommonHelper::getEditorPath() . '/' . $fileName),
        ], Response::HTTP_OK);
    }
}
