<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\AdminBaseController;

class ConfigurationController extends AdminBaseController
{
    public function index($group): View
    {
        return $this->settingForm(strtoupper($group));
    }

    public function save($group, Request $request): RedirectResponse
    {
        return $this->saveSettingForm($request, strtoupper($group));
    }

    protected function settingForm(string $group): View
    {
        $settings = Configuration::where('group', $group)->pluck('value', 'key')->toArray();
        $settingValues = Configuration::getSetting($group);
        $title = Configuration::getGroup($group);

        return view('admin.configuration.index', [
            'title' => $title,
            'group' => $group,
            'settings' => $settings,
            'settingValues' => $settingValues,
        ]);
    }

    protected function saveSettingForm(Request $request, string $group): RedirectResponse
    {
        $settingValues = Configuration::getSetting($group);
        $validationArgs = [];

        if ($settingValues) {
            foreach ($settingValues as $requiredKey => $requiredField) {
                $type = $requiredField['type'] ?? 'text';

                if (in_array($type, ['button', 'submit'])) {
                    continue;
                }

                $name = $requiredField['name'] ?? $requiredKey;
                $required = (bool) ($requiredField['required'] ?? false);

                if ($type === 'file') {
                    if (in_array($name, ['SITE_LOGO', 'SITE_FOOTER_LOGO'])) {
                        $validationArgs[$name] = 'image|mimes:jpeg,png,jpg,gif|max:2048'; // Adjust max file size as needed
                    }
                }

                if ($required) {
                    $validationArgs[$name] = 'required';
                }
            }
        }

        if ($validationArgs) {
            $validatedData = $request->validate($validationArgs);
        }

        foreach ($settingValues as $key => $value) {
            $type = $value['type'] ?? 'text';
            $name = $value['name'] ?? $key;

            if (in_array($type, ['button', 'submit'])) {
                continue;
            }

            $defaultValue = $value['default_value'] ?? null;
            $postedValue = $request->get($name, $defaultValue);

            if ($request->has('delete_' . strtolower($key))) {
                $this->deleteLogo($postedValue);
                $postedValue = null;
            }

            if ($type === 'file') {
                $uploadedFile = $request->file($name);

                if ($uploadedFile instanceof UploadedFile) {
                    $path = $uploadedFile->store('public/uploads');
                    $postedValue = Storage::url($path);
                } elseif (! $request->has('delete_' . strtolower($key))) {
                    $postedValue = config('global.' . $name);
                }
            }

            $model = Configuration::firstOrNew([
                'group' => $group,
                'key' => $key,
            ]);

            if (!$model instanceof Configuration) {
                continue;
            }

            $model->value = $postedValue;
            $model->save();
        }

        return redirect()
            ->route('admin.configuration', [strtolower($group)])
            ->with('message', 'Setting successfully saved.');
    }

    protected function deleteLogo($path): void
    {
        $filePath = public_path($path);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}
