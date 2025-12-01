<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait UserControllerTrait
{
    private function getRoute($user): string
    {
        $route = null;

        if ($user->role == User::ROLE_TYPE_EMPLOYEE) {
            $route = 'admin.user.employee.index';
        }

        return $route;
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::find($id);
        $route = $this->getRoute($user);

        if (! $user instanceof User) {
            return redirect()->route($route)
                ->with('global_error', __('Record not found.'));
        }

        $user->delete();
        $message = sprintf(__('%s - %s successfully delete.'), User::getRole($user->role), $user->name);

        return redirect()
            ->route($route)
            ->with('message', $message);
    }

    public function login($id): RedirectResponse
    {
        $user = User::find($id);
        $route = $this->getRoute($user);

        if (! $user instanceof User) {
            return redirect()->route($route)
                ->with('global_error', __('Record not found.'));
        }

        $auth = 'employee';

        auth($auth)->login($user);
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        $message = sprintf(__('%s - %s successfully logegd in.'), User::getRole($user->role), $user->name);

        return redirect()
            ->route('home')
            ->with('message', $message);
    }

    public function profileImage($id)
    {
        $user = User::find($id);

        if (! $user instanceof User) {
            return response('', HttpResponse::HTTP_NOT_FOUND);
        }

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
