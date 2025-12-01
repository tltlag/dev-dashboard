<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AccountController extends AdminBaseController
{
    public function edit(): view
    {
        return view('admin.account.edit', [
            'user' => auth('admin')->user()
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth('admin')->user();
        $id = $user->id;

        $validated = $request->validate([
            'profile_image_file' => 'file|mimes:jpeg,png,jpg|max:2048',
            'username' => [
                'required',
                'max:255',
                'regex:/' . User::USERNAME_REGEX . '/',
                Rule::unique('admins', 'username')->ignore($id, 'id'),
            ],
            'password' => 'nullable|confirmed|regex:/' . User::PASSWORD_REGEX . '/',
            'email' => [
                'required',
                'email',
                Rule::unique('admins', 'email')->ignore($id, 'id'),
            ],
            'name' => 'required|max:255',
        ], [
            'username.regex' => __(User::USERNAME_HINT_MESSAGE),
            'password.regex' => __(User::PASSWORD_HINT_MESSAGE),
            'profile_image_file.file' => __('Please upload profile image.'),
            'profile_image_file.mimes' => __('Please upload valid profile image.'),
            'profile_image_file.max' => __('The profile image size has been exceeded.'),
        ]);

        $postedData = $request->all();

        if ($postedData['password']) {
            $postedData['password'] = Hash::make($postedData['password']);
        } else {
            unset($postedData['password']);
        }

        $profileImageFile = $request->file('profile_image_file');

        if ($profileImageFile) {
            $fileName = time() . '_' . $profileImageFile->getClientOriginalName();
            $profileImageFile->storeAs(CommonHelper::getUserProfilePath(), $fileName);

            if ($fileName) {
                $postedData['profile_image'] = CommonHelper::getUserProfilePath() . '/' . $fileName;
            }
        }

        $user->fill($postedData);
        $user->save();
     
        return back()->with('message', __('You profile successfully updated.'));
    }

    public function profileImage()
    {
        $user = auth('admin')->user();
        $id = $user->id;

        if (! $user->profile_image) {
            $path = public_path('frontend-assets/images/user-no-img.png');
        } else {
            $path = storage_path('app/' . $user->profile_image);
        }

        try {
            if (! File::exists($path)) {
                $path = public_path('frontend-assets/images/user-no-img.png');
            }

            if (! File::exists($path)) {
                return response('', HttpResponse::HTTP_NOT_FOUND);
            }

            $file = File::get($path);
            $type = File::mimeType($path);
        } catch (Exception $e) {
            return response('', HttpResponse::HTTP_NOT_FOUND);
        }

        $response = Response::make($file, HttpResponse::HTTP_OK);
        $response->header("Content-Type", $type);

        return $response;
    }
}