@extends('layouts.app')

@section('title'){{ $post->title }}@endsection

@section('content')
    <h1>{{ $post->title }}</h1>
    <h5>{{ $post->updated_at->format('d/m/Y g:ia') }}</h5>
    <hr>
    {!! nl2br($post->content) !!}
    <hr>
    <button class="btn btn-primary" onclick="history.go(-1)">
        « Back
    </button>
@endsection