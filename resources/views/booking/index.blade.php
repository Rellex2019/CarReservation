@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Бронирование служебного автомобиля</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('booking.search') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">Дата начала поездки {{ now() }}</label>
                        <input type="date" value="{{ $startDate ?? '' }}" min="{{ now()->toDateString() }}"  class="form-control" name="start_date" id="start_date" required>
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">Дата окончания поездки</label>
                        <input type="date" value="{{ $endDate ?? '' }}" class="form-control" name="end_date" id="end_date" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Найти доступные авто</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @isset($availableCars)
    <div class="card">
        <div class="card-header">
            <h3>Доступные автомобили с {{ $startDate }} по {{ $endDate }}</h3>
        </div>
        <div class="card-body">
            @if($availableCars->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Модель</th>
                            <th>Категория</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableCars as $car)
                        <tr>
                            <td>{{ $car->model }}</td>
                            <td>{{ $car->category->name }}</td>
                            <td>
                                <form action="{{ route('booking.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                                    <button type="submit" class="btn btn-success btn-sm">Забронировать</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                На выбранные даты нет доступных для вас автомобилей.
            </div>
            @endif
        </div>
    </div>
    @endisset

    <h3 class="mt-4">Мои бронирования</h3>
    <div class="card">
        <div class="card-body">
            @if($userBookings->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Автомобиль</th>
                        <th>Даты</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userBookings as $booking)
                    <tr>
                        <td>{{ $booking->car->model }}</td>
                        <td>{{ $booking->start_date }} - {{ $booking->end_date }}</td>
                        <td>
                            <span class="badge 
                                @if($booking->status == 'confirmed') bg-success
                                @else bg-secondary
                                @endif">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td>
                            @if($booking->status == 'confirmed')
                            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Отменить</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-info">
                У вас нет активных бронирований.
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
        startDateInput.addEventListener('change', function () {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });
    });
</script>
@endsection