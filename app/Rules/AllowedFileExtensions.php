<?php

namespace App\Rules;

use App\Helpers\CommonHelper;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class AllowedFileExtensions implements Rule
{
    public function passes($attribute, $value)
    {
        if (! $value instanceof UploadedFile) {
            return false;
        }

        $extension = $value->getClientOriginalExtension();
        $mime = $value->getMimeType();

        return in_array(
            $extension,
            CommonHelper::getValidExtensions()
        ) &&
        in_array(
            $mime,
            array_values(CommonHelper::VALID_UPLOAD_MIME_TYPES),
        );
    }

    public function message()
    {
        return __('Please upload valid :attribute file.');
    }
}