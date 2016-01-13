@if (Session::has('message'))
    <div class="flash alert-info">
        <p class="panel-body">
            {{ Session::get('message') }}
        </p>
    </div>
@endif