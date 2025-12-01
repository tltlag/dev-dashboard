<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeBaseController extends Controller
{
    protected ?Builder $collection = null;

    public function dataTableList(Request $request)
    {
        $draw = $request->get('draw');

        if (! $this->collection instanceof Builder) {
            return response()->json([
                'draw' => intval($draw),
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => null
            ], Response::HTTP_OK);
        }

        $totalRecords = $this->collection->count();

        if (is_callable([$this, 'dataTableCollection'])) {
            $this->dataTableCollection($request);
        }

        $totalRecordswithFilter = $this->collection->count();
        $start = $request->get('start');
        $perPage = $request->get('length');

        $records = $this->collection
            ->skip($start)
            ->take($perPage)
            ->get()
            ->toArray();

        if (method_exists($this, 'modifyDataTableRecords')) {
            $records = $this->modifyDataTableRecords($records, $request);
        }

        return response()->json([
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $records
        ], Response::HTTP_OK);
    }
}
