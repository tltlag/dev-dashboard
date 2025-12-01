<?php

namespace App\Traits;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

trait BulkUpdateControllerTrait
{
    public function bulkUpdate(Request $request)
    {
        if (! (isset($this->model) && $this->model && class_exists($this->model))) {
            return response()->json([
                'status' => false,
                'message' => __('Invalid request.'),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'ids' => 'required',
            'status' => 'required',
        ], [
            'ids.required' => __('Please select at least one record.'),
            'status.in' => __('Please select an action.'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $status = $request->get('status');
        $ids = $request->get('ids');

        if (in_array(
            $status, [
                CommonHelper::BULK_ACTION_ACTIVE,
                CommonHelper::BULK_ACTION_IN_ACTIVE,
            ]
        )) {
            if (! method_exists($this->model, 'getStatusList')) {
                return response()->json([
                    'status' => false,
                    'message' => __('Invalid request.'),
                ]);
            }

            return $this->updateBulkStatus($status, $ids);
        } elseif($status == CommonHelper::BULK_ACTION_DELETE) {
            return $this->deleteBulk($ids);
        }

        return response()->json([
            'status' => false,
            'message' => __('Invalid request.'),
        ]);
    }

    protected function updateBulkStatus(string $status, array $ids)
    {
        $status = ($status === CommonHelper::BULK_ACTION_ACTIVE) ? $this->model::STATUS_ACTIVE : $this->model::STATUS_IN_ACTIVE;

        $this->model::whereIn('id', $ids)->update([
            'status' => $status,
        ]);

        return response()->json([
            'status' => true,
            'message' => __('Selected records successfully updated.'),
        ], Response::HTTP_OK);
    }

    protected function deleteBulk(array $ids)
    {
        $this->model::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => true,
            'message' => __('Selected records successfully deleted.'),
        ], Response::HTTP_OK);
    }
}
