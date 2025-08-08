<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingDateRequest;
use App\Http\Requests\BookingStoreRequest;
use App\Models\Booking;
use App\Models\Car;

class BookingController extends Controller
{
    public function index()
    {
        $userBookings = auth()->user()->bookings()
            ->with('car')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('booking.index', [
            'userBookings' => $userBookings,
        ]);
    }

    public function search(BookingDateRequest $request)
    {

        $availableCars = Car::getAvailableCars(
            $request['start_date'],
            $request['end_date'],
            $request->user()
        )->get();

        return view('booking.index', [
            'availableCars' => $availableCars,
            'startDate' => $request['start_date'],
            'endDate' => $request['end_date'],
            'userBookings' => auth()->user()->bookings,
        ]);
    }

    public function store(BookingStoreRequest $request)
    {

        $car = Car::findOrFail($request['car_id']);

        if (! $car->isAvailable($request['start_date'], $request['end_date'])) {
            return back()->with('error', 'Этот автомобиль уже забронирован на выбранные даты');
        }
        Booking::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'status' => 'confirmed',
        ]);

        return redirect()->route('booking.index')
            ->with('success', 'Автомобиль успешно забронирован.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Можно отменять только бронирования в статусе "Confirmed"');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Бронирование отменено');
    }
}
