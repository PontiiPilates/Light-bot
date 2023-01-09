@extends('admin.index')

@section('title', 'Адреса и контакты')

@section('content')

{{-- Message --}}
@if(session()->has('message'))
    <div class="alert alert-success" role="alert">{{ session()->get('message') }}</div>
@endif
{{-- End Message --}}

<p>{{ $about->description ?? ''}}</p>

<a href="{{ route('admin.about.edit') }}" class="btn btn-dark">Редактировать</a>

@endsection