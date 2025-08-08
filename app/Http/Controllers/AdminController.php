<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Category;
use App\Models\JobPosition;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {

        $totalCars = Car::count();

        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $availableCars = Car::getAvailableCars($startDate, $endDate);
        $availableNow = $availableCars->count();

        $activeBookings = Booking::where('status', 'confirmed')->count();

        $jobPositions = JobPosition::with('categories')->get();
        $categories = Category::all();

        return view('admin.dashboard', [
            'totalCars' => $totalCars,
            'availableNow' => $availableNow,
            'activeBookings' => $activeBookings,
            'jobPositions' => $jobPositions,
            'categories' => $categories,
        ]);

    }

    public function updatePositionCategories(Request $request, $positionId)
    {
        $position = JobPosition::findOrFail($positionId);

        $data = $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $position->categories()->sync($data['categories'] ?? []);

        return response()->json(['message' => 'Категории успешно обновлены']);
    }
}
