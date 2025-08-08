@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Управление автомобилями</h1>
        <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Добавить автомобиль
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Модель</th>
                            <th>Гос. номер</th>
                            <th>Цена/день</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cars as $car)
                        <tr>
                            <td>{{ $car->model }}</td>
                            <td>{{ $car->license_plate }}</td>
                            <td>{{ number_format($car->price_per_day, 2) }} руб.</td>
                            <td>
                                @if($car->is_active)
                                <span class="badge bg-success">Активен</span>
                                @else
                                <span class="badge bg-secondary">Неактивен</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.cars.availability', $car->id) }}" 
                                       class="btn btn-info btn-sm" title="Управление доступностью">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                </div>
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