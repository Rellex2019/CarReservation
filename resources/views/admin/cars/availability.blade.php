@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Управление доступностью: {{ $car->model }} ({{ $car->license_plate }})
        </h1>
        <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Добавить период недоступности</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cars.block-dates', $car->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">Дата начала</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" required>
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">Дата окончания</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" required>
                    </div>
                    <div class="col-md-4">
                        <label for="reason">Причина</label>
                        <input type="text" class="form-control" name="reason" id="reason" placeholder="ТО, ремонт...">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Заблокировать даты</button>
            </form>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Заблокированные периоды</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Даты</th>
                            <th>Причина</th>
                            <th>Добавлено</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unavailablePeriods as $period)
                        <tr>
                            <td>{{ $period->start_date }} - {{ $period->end_date }}</td>
                            <td>{{ $period->reason ?? 'Не указана' }}</td>
                            <td>{{ $period->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.cars.unblock-dates', $period->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-unlock"></i> Разблокировать
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection