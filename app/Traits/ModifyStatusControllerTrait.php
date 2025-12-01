<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

trait ModifyStatusControllerTrait
{
    public function updateStatus($id, Request $request)
    {
        if (! (isset($this->model) && $this->model)) {
            return response()->json([
                'status' => false,
                'message' => __('Invalid request.'),
            ]);
        }

        if (! method_exists($this->model, 'getStatusList')) {
            return response()->json([
                'status' => false,
                'message' => __('Invalid request.'),
            ]);
        }

        $model = $this->model::find($id);

        if (! $model instanceof $this->model) {
            return response()->json([
                'status' => false,
                'message' => __('Record not found.'),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', $this->model::getStatusList()),
        ], [
            'status.required' => __('Please change the status.'),
            'status.in' => __('Not a valid status.'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $status = $request->get('status');
        $model->fill(['status' => $status]);
        $model->save();

        return response()->json([
            'status' => true,
            'message' => __('Status Successfully Updated.'),
            'status_text' => $model->getStatus($model->status),
        ], Response::HTTP_OK);
    }
}
