<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailableCarRequest;
use App\Models\Car;

class CarController extends Controller
{
    public function available(AvailableCarRequest $request)
    {
        $cars = Car::getAvailableCars($request['start_time'], $request['end_time'], $request->user());

        if (! empty($request['model'])) {
            $cars->where('model', 'like', '%'.$request['model'].'%');
        }
        if (! empty($request['comfort_category_id'])) {
            $cars->where('comfort_category_id', $request['comfort_category_id']);
        }

        $availableCars = $cars->get();

        return response()->json([
            'available_cars' => $availableCars,
        ]);
    }
}
