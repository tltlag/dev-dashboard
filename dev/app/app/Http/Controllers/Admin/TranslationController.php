<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\GenerateTranslationFilesJob;

class TranslationController extends AdminBaseController
{
    public function index()
    {
        $translations = Translation::all();
        return view('admin.translations.index', compact('translations'));
    }

    public function list(Request $request)
    {
        $this->collection = Translation::query();

        return $this->dataTableList($request);
    }

    protected function dataTableCollection(Request $request): void
    {
        $keywords = $request->get('keywords', null);
        $lcoale = $request->get('lcoale', null);

        $orderArr = $request->get('order');
        $orderArr = $orderArr ? reset($orderArr) : [];

        if (isset($orderArr['column']) && isset($orderArr['dir'])) {
            $dataTableFields = [
                'checkbox',
                'id',
                'key',
                'locale',
                'value',
            ];

            $columnName = $dataTableFields[$orderArr['column']] ?? 'created_at';
            $columnName = in_array($columnName, ['checkbox', 'action']) ? 'created_at' : $columnName;
            $columnSortOrder = $orderArr['dir'];

            $this->collection->orderBy($columnName, $columnSortOrder);

            if ($columnName == 'created_at') {
                $this->collection->orderBy('id', $columnSortOrder);
            }
        }

        if ($keywords) {
            $this->collection->where(function ($query) use ($keywords) {
                $query->where('key', 'LIKE', "%$keywords%");
                $query->orWhere('value', 'LIKE', "%$keywords%");
            });
        }

        if ($lcoale) {
            $this->collection->where('locale', 'like', $lcoale);
        }
    }

    protected function modifyDataTableRecords(?array $records = null, Request $request): array
    {
        if (! $records) {
            return [];
        }

        foreach ($records as & $record) {
            $record['checkbox'] = '<label><input type="checkbox" value="' . $record['id'] . '" class="list-ids" /></label>';
            $record['action'] = '<a href="' . route('admin.translations.edit', [$record['id']]) . '" title="' . __('Edit') . '" class="btn btn-primary"><i class="lni lni-pencil"></i></a> <form action="' . route('admin.translations.destroy', $record['id']) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'' . __('Are you sure?') . '\');"><input type="hidden" name="_token" value="' . csrf_token() . '"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="btn btn-danger"><i class="lni lni-trash-can"></i></button></form>';
        }

        return $records;
    }

    public function create()
    {
        return view('admin.translations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255',
            'locale' => 'required|string|max:2',
            'value' => 'required|string',
        ]);

        $model = Translation::where('key', 'like', $request->get('key', null))
            ->where('locale', 'like', $request->get('locale', null))
            ->first();
        
        if ($model instanceof Translation) {
            return redirect()->back()->withInput($request->all())->with('global_error', __('Translation already exists.'));
        }

        Translation::create($request->all());
        GenerateTranslationFilesJob::dispatch();

        return redirect()->route('admin.translations.index')->with('message', __('Translation added successfully.'));
    }

    public function edit(Translation $translation)
    {
        return view('admin.translations.edit', compact('translation'));
    }

    public function update(Request $request, Translation $translation)
    {
        $request->validate([
            'value' => 'required|string',
        ]);

        $model = Translation::where('key', 'like', $request->get('key', null))
            ->where('locale', 'like', $request->get('locale', null))
            ->where('id', '!=', $translation->id)
            ->first();
        
        if ($model instanceof Translation) {
            return redirect()->back()->withInput($request->all())->with('global_error', __('Translation already exists.'));
        }

        $translation->update($request->all());
        GenerateTranslationFilesJob::dispatch();

        return redirect()->route('admin.translations.index')->with('message', __('Translation updated successfully.'));
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();
        return redirect()->route('admin.translations.index')->with('message', __('Translation deleted successfully.'));
    }

    public function sync()
    {
        GenerateTranslationFilesJob::dispatch();
        return redirect()->back()->with('message', __('Translations added in queue will be synced shortly successfully.'));
    }
}
