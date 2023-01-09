@extends('admin.index')

@section('title', 'Расписание программ')

@section('content')

{{-- Timetable --}}
@foreach ($timetable as $item)

    @php
        $months = [
            '01' => 'января',
            '02' => 'февраля',
            '03' => 'марта',
            '04' => 'апреля',
            '05' => 'мая',
            '06' => 'июня',
            '07' => 'июля',
            '08' => 'августа',
            '09' => 'сентября',
            '10' => 'октября',
            '11' => 'ноября',
            '12' => 'декабря',
        ];
        $date = explode('-', $item->date);
        $date = $date[2] . ' ' . $months[$date[1]];
    @endphp

    {{-- Если итерация первая --}}
    @if ( $loop->first )
        <p>🗓 {{ $date }}</p>
    @endif

    {{-- Если итерация вторая и больше --}}
    @if ( $loop->index > 0 )

        {{-- Если текущий день не равен предыдущему --}}
        @if ( $item->date != $timetable[$loop->index-1]->date )
            <p>🗓 {{ $date }}</p>
        @endif

    @endif

    <p>{{ Str::limit($item->time, 5, false) }} {{ $item->name }}</p>

@endforeach
{{-- End Timetable --}}

@endsection