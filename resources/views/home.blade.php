@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div><a href="/events"><i class="fa fa-4x fa-calendar" aria-hidden="true"></i> Check your events</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
