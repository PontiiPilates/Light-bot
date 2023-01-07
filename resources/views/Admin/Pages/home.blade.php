@extends('admin.index')

@section('title', 'Главная')

@section('content')

<div class="container row">

    {{-- User count --}}
    <div class="col-4">
        <p>Количество пользователей:</p>
        <p>{{ $data['people_started'] }}</p>
    </div>
    {{-- End User count --}}

    {{-- Users reads --}}
    <div class="col-4">
        <p>Пользователи пишут:</p>
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
    </div>
    {{-- End Users reads --}}

    {{-- Users activity --}}
    <div class="col-4">
        <p>Активность запросов:</p>
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
    </div>
    {{-- End Users activity --}}

</div>

@endsection