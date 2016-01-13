@extends('layouts.app')

@section('title') Add New Post @endsection

@section('content')

    <H1>Add New Post</H1>

    <div class="panel-body">
        <!-- New Post Form -->
        <form action="/new-post" method="post">
            {{ csrf_field() }}

            <div class="form-group">
                <input required="required" value="{{ old('title') }}" placeholder="Enter title here" type="text"
                       name="title" class="form-control"/>
            </div>
            <div class="form-group">
                <textarea name='content' class="form-control">{{ old('content') }}</textarea>
            </div>
            <input type="submit" name='publish' class="btn btn-success" value="Publish"/>
            <input type="submit" name='save' class="btn btn-default" value="Save Draft"/>
        </form>
    </div>
@endsection