<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Traits\UserControllerTrait;
use App\Http\Controllers\Admin\AdminBaseController;

class EmployeeController extends AdminBaseController
{
    use UserControllerTrait;

    protected int $role = User::ROLE_TYPE_EMPLOYEE;
    protected string $model = User::class;

    public function index(): View
    {
        $status = User::getStatusListOptions();

        return view('admin.user.employee.index', [
            'status' => $status,
        ]);
    }

    public function list(Request $request)
    {
        $this->collection = User::query();

        return $this->dataTableList($request);
    }

    protected function dataTableCollection(Request $request): void
    {
        $keywords = $request->get('keywords', null);
        $status = $request->get('status', null);

        $orderArr = $request->get('order');
        $orderArr = $orderArr ? reset($orderArr) : [];

        if (isset($orderArr['column']) && isset($orderArr['dir'])) {
            $dataTableFields = [
                'checkbox',
                'sr',
                'name',
                'username',
            ];

            $columnName = $dataTableFields[$orderArr['column']] ?? 'created_at';
            $columnName = in_array($columnName, ['checkbox', 'sr', 'address', 'status', 'action']) ? 'created_at' : $columnName;
            $columnSortOrder = $orderArr['dir'];

            $this->collection->orderBy($columnName, $columnSortOrder);

            if ($columnName == 'created_at') {
                $this->collection->orderBy('id', $columnSortOrder);
            }
        }

        if ($keywords) {
            $this->collection->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', "%$keywords%");
                $query->orWhere('email', 'LIKE', "%$keywords%");
                $query->orWhere('username', 'LIKE', "%$keywords%");
            });
        }

        $this->collection->where('role', $this->role);

        if ($status !== null) {
            $this->collection->where('status', $status);
        }
    }

    protected function modifyDataTableRecords(?array $records = null, Request $request): array
    {
        if (! $records) {
            return [];
        }

        $srNo = $request->get('start', 0) + 1;

        foreach ($records as & $record) {
            $record['checkbox'] = '<label><input type="checkbox" value="' . $record['id'] . '" class="list-ids" /></label>';
            $record['sr'] = $srNo;
            $record['username'] .= " (# {$record['id']})";
            $record['username'] .= '<br/><a href="mailto:' . $record['email'] . '">' . $record['email'] . '</a>';
            $record['username'] .= '<br/><a href="tel:' . $record['phone'] . '">' . $record['phone'] . '</a>';
            $record['action'] = '<a href="' . route('admin.sync.wildix.calls', [$record['id']]) . '" title="' . __('Sync Call History') . '" class="mr-3 btn btn-primary">' . __('Sync Call History') . '</a>';
            $srNo++;
        }

        return $records;
    }

    public function search(Request $request)
    {
        $srch = $request->get('term');

        $collection = User::where('role', User::ROLE_TYPE_EMPLOYEE)
            ->where('status', User::STATUS_ACTIVE);

        if ($srch) {
            $collection->where('name', 'LIKE', "%$srch%");
        }

        $collection->take(10);
        $collection->orderBy('name', 'ASC');
        $collection->selectRaw(DB::raw('`id`, `name` as `text`'));
        $records = $collection->get()->toArray();

        return response()->json([
            'results' => $records,
        ]);
    }
}
