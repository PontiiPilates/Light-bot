@extends('admin.index')

@section('title', 'Главная')

@section('content')


@if(session()->has('message'))
<div class="alert alert-success" role="alert">{{ session()->get('message') }}</div>
@endif

@if ( stripos(url()->current(), '/edit') )
<form class="row g-3" method="POST" action="{{ route('program.edit', ['id' => $id]) }}">
    @elseif( stripos(url()->current(), '/create'))
    <form class="row g-3" method="POST" action="{{ route('program.create') }}">
        @endif

        @csrf

        <div class="col-12">
            <label for="name" class="form-label">Название программы</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $program->name ?? '' }}">
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Описание программы</label>
            <textarea class="form-control" id="description" placeholder="Описание"
                name="description">{{ $program->description ?? '' }}</textarea>
        </div>

        <div class="col-12">
            <label for="price" class="form-label">Описание стоимости</label>
            <textarea class="form-control" id="price" placeholder="Описание"
                name="price">{{ $program->price ?? '' }}</textarea>
        </div>


        {{-- Date & time --}}
        <div class="col-12 timetable">
            @if( isset($timetable) )
            @foreach ($timetable as $item)
            <div class="row row-timetable-container mb-3">
                {{-- <div class="col-6">
                    <label for="date" class="form-label">Дата</label>
                    <input type="date" class="form-control" id="date" name="date[]" value="{{ $item->date }}">
                </div> --}}
                <div class="col-6">
                    <label for="day" class="form-label">День</label>
                    <select type="day" class="form-select" id="day" name="day[]">
                        <option value="Пн" @if($item->day == 'Пн') selected @endif>Пн</option>
                        <option value="Вт" @if($item->day == 'Вт') selected @endif>Вт</option>
                        <option value="Ср" @if($item->day == 'Ср') selected @endif>Ср</option>
                        <option value="Чт" @if($item->day == 'Чт') selected @endif>Чт</option>
                        <option value="Пт" @if($item->day == 'Пт') selected @endif>Пт</option>
                        <option value="Сб" @if($item->day == 'Сб') selected @endif>Сб</option>
                        <option value="Вс" @if($item->day == 'Вс') selected @endif>Вс</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="time" class="form-label">Время</label>
                    <input type="time" class="form-control" id="time" name="time[]" value="{{ Str::limit($item->time, 5, false) }}">
                </div>
                <button type="button" class="btn btn-link remove"><i class="bi bi-x-circle"></i></button>
            </div>
            @endforeach
            @else
            <div class="row row-timetable-container mb-3">
                {{-- <div class="col-6">
                    <label for="date" class="form-label">Дата</label>
                    <input type="date" class="form-control" id="date" name="date[]">
                </div> --}}
                <div class="col-6">
                    <label for="day" class="form-label">День</label>
                    <select type="day" class="form-select" id="day" name="day[]">
                        <option value="Пн">Пн</option>
                        <option value="Вт">Вт</option>
                        <option value="Ср">Ср</option>
                        <option value="Чт">Чт</option>
                        <option value="Пт">Пт</option>
                        <option value="Сб">Сб</option>
                        <option value="Вс">Вс</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="time" class="form-label">Время</label>
                    <input type="time" class="form-control" id="time" name="time[]">
                </div>
                <button type="button" class="btn btn-link remove"><i class="bi bi-x-circle"></i></button>
            </div>
            @endif
        </div>

        <div class="col-12">
            <button type="button" class="btn btn-link add text-start p-0">Добавить расписание</button>
        </div>

        {{-- End Date & time --}}

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck">
                <label class="form-check-label" for="gridCheck">Опубликовано</label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <button type="submit" class="btn btn-primary">Удалить</button>
        </div>

    </form>






    <style>
        .row-timetable-container {
            position: relative;
        }

        .remove {
            position: absolute;
            bottom: 0;
            right: -38px;
            width: 38px;
        }
    </style>




    <script>
        $(document).ready(function () {

    $('html').on('click','.add',function () {
        let fields = '<div class="row row-timetable-container mb-3"><div class="col-6"><label for="day" class="form-label">День</label><select type="day" class="form-select" id="day" name="day[]"><option value="Пн">Пн</option><option value="Вт">Вт</option><option value="Ср">Ср</option><option value="Чт">Чт</option><option value="Пт">Пт</option><option value="Сб">Сб</option><option value="Вс">Вс</option></select></div><div class="col-6"><label for="time" class="form-label">Время</label><input type="time" class="form-control" id="time" name="time[]"></div><button type="button" class="btn btn-link remove"><i class="bi bi-x-circle"></i></button></div>';
        $(fields).fadeIn('slow').appendTo('.timetable');
    });

    $('html').on('click','.remove', function () {
        $(this).parent().fadeOut('slow').remove();
    });

});
    </script>



























    @endsection