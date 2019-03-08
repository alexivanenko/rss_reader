@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3><a href="{{ $rss['link'] }}" target="_blank">{{ $rss['title'] }}</a></h3>
                    <div style="padding-top: 10px;padding-bottom: 10px; text-align: center;">
                        @foreach ($key_words as $word => $usages)
                            <span class="badge badge-success" style="color: white;"><h6>{{ $word}}: {{ $usages }}</h6></span>
                        @endforeach
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @foreach ($rss['items'] as $item)
                        <div class="item">
                            <h4><a href="{{ $item->get_permalink() }}" target="_blank">{{ $item->get_title() }}</a></h4>
                            <p>{!! $item->get_description() !!}</p>
                            <p><small>Posted on {{ $item->get_date('j F Y | H:i') }} by <a href="{{ $item->get_author()->get_link() }}" target="_blank">{{ $item->get_author()->get_name() }}</a></small></p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
