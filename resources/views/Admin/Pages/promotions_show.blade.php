@extends('admin.index')

@section('title', 'Акции')

@section('content')

{{-- Timetable --}}
@foreach ($promotions as $item)
    <p><b>{{ $item->name }}</b></p>
    <p> {{ $item->description }}</p><br>
@endforeach
{{-- End Timetable --}}

@endsection