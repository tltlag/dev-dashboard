<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

trait OrderingRowsControllerTrait
{

    protected function canProceed()
    {
        return (bool) (
            isset($this->model) && 
            $this->model && 
            class_exists($this->model) && 
            (new $this->model())->canReOrder()
        );
    }

    public function updateOrder(Request $request)
    {
        if (! $this->canProceed()) {
            return response()->json([
                'status' => false,
                'message' => __('Invalid request.'),
            ]);
        }

        $table = (new $this->model())->getTable();

        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
        ], [
            'ids.required' => __('Please try again.'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $ids = $request->get('ids');
        $previousOrders = $this->model::whereIn('id', $ids)->pluck('order', 'id')->toArray();

        $newOrders = [];
        $previous = 0;

        foreach ($ids as $id) {
            $min = min($previousOrders);

            if ($min) {
                $previous = $min;
                unset($previousOrders[array_search($min, $previousOrders)]);
            } else {
                $previous++;
            }

            $this->model::find($id)->update(['order' => $previous]);
        }

        return response()->json([
            'status' => true,
            'message' => __('Records order updated.'),
        ]);
    }
}
