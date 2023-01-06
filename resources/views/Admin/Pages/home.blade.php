@extends('admin.index')

@section('title', 'Главная')

@section('content')
<b>Бот стартовал:</b> {{ $data['people_started']}}

<ul>
    @foreach ( $data['messages'] as $v)
    <li>
        <div>
            <b>Message:</b> {{ $v['text'] }}
        </div>
        <div>
            <b>Date:</b> {{ $v['created_at'] }}
        </div>
        <div>
            <b>From:</b> {{ $v['from_id'] }}
        </div>
    </li>
    @endforeach
</ul>


@endsection