@extends('layouts.app') @section('content')
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
                    <div class="row">

                        <div class="col-md-6 text-center col-sm-12">
                            <div>
                                <a href="/events">
                                    <i class="fa fa-4x fa-calendar" aria-hidden="true"></i> Check your events</a>
                            </div>
                        </div>
                        <div class="col-md-6 text-center col-sm-12">
                            <div>
                                <a href="/categories">
                                    <i class="fa fa-4x fa-tag" aria-hidden="true"></i> Categories</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection