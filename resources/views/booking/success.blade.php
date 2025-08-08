@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Бронирование подтверждено</div>

                <div class="card-body">
                    <div class="alert alert-success">
                        Вы успешно забронировали автомобиль {{ $booking->car->model }} ({{ $booking->car->license_plate }})
                        на период с {{ $booking->start_date->format('d.m.Y') }} по {{ $booking->end_date->format('d.m.Y') }}.
                    </div>
                    
                    <a href="{{ route('booking.index') }}" class="btn btn-primary">Вернуться к бронированию</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection