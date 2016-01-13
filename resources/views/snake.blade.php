@extends('layouts.app')

@section('title') Snake @endsection

@section('head')


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: 'Lato';
            padding: 0;
            margin: 0;
        }

         body{
             padding: 0;
             margin: 0;
         }

        canvas{
            margin: 0 auto;
        }
    </style>

@endsection

@section('content')

    <h1>Snake</h1>

    <script type="text/javascript" src="//cdn.jsdelivr.net/phaser/2.4.4/phaser.min.js"></script>
    <script type="text/javascript" src="js/all.js"></script>

@endsection