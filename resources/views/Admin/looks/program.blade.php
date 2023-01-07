@extends('admin.index')

@section('title', $program->name)

@section('content')

<p><b>{{ $program->name }}</b></p>
<p>{{ $program->description }}</p>

@foreach ($timetable as $item)
    <p>{{ $item->day }} - {{ Str::limit($item->time, 5, false) }}</p>
@endforeach

<p><i>{{ $program->price }}</i></p>

<a href="{{ route('admin.control.program', ['id' => $program->id]) }}" class="btn btn-dark">Редактировать</a>


@endsection