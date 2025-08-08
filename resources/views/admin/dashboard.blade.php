@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">Панель управления автопарком</h1>

        <div class="row mt-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h5>Всего автомобилей</h5>
                        <h2>{{ $totalCars }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <h5>Доступно сейчас</h5>
                        <h2>{{ $availableNow }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <h5>Активных бронирований</h5>
                        <h2>{{ $activeBookings }}</h2>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Управление доступными категориями по должностям
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="positionsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Должность</th>
                                <th>Доступные категории машин</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobPositions as $position)
                                <tr>
                                    <td>{{ $position->name }}</td>
                                    <td>
                                        @foreach ($categories as $category)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input position-category" type="checkbox"
                                                    data-position-id="{{ $position->id }}"
                                                    data-category-id="{{ $category->id }}"
                                                    id="pos{{ $position->id }}_cat{{ $category->id }}"
                                                    {{ $position->categories->contains($category->id) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="pos{{ $position->id }}_cat{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary save-categories"
                                            data-position-id="{{ $position->id }}">
                                            Сохранить
                                        </button>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            console.log(typeof jQuery);

            // Настройка CSRF заголовка для всех AJAX запросов
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Обработчик клика с делегированием
            $(document).on('click', '.save-categories', function() {
                console.log('Кнопка сохранения нажата');
                const positionId = $(this).data('position-id');
                const checkedCategories = [];

                $(`.position-category[data-position-id="${positionId}"]:checked`).each(function() {
                    checkedCategories.push($(this).data('category-id'));
                });

                $.ajax({
                    url: '/admin/positions/' + positionId + '/update-categories',
                    method: 'POST',
                    data: {
                        categories: checkedCategories
                    },
                    success: function(response) {
                        toastr.success('Настройки сохранены');
                    },
                    error: function() {
                        toastr.error('Ошибка при сохранении');
                    }
                });
            });

            // Быстрое сохранение при изменении чекбокса
            $(document).on('change', '.position-category', function() {
                $(this).closest('tr').find('.save-categories').click();
            });
        });
    </script>
@endpush
