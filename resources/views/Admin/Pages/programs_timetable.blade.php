@extends('admin.index')

@section('title', 'Расписание программ')

@section('content')

{{-- Timetable --}}
@foreach ($timetable as $item)

    {{-- Если итерация первая --}}
    @if ( $loop->first )
        <p>🗓 {{ $item->day }}</p>
    @endif

    {{-- Если итерация вторая и больше --}}
    @if ( $loop->index > 0 )

        {{-- Если текущий день не равен предыдущему --}}
        @if ( $item->day != $timetable[$loop->index-1]->day )
            <p>🗓 {{ $item->day }}</p>
        @endif

    @endif

    <p>{{ Str::limit($item->time, 5, false) }} {{ $item->name }}</p>

@endforeach
{{-- End Timetable --}}

@endsection