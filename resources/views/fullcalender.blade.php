@extends('layouts.app') @section('styles')
<link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet"> @endsection @section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script> @endsection @section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      {!! Form::open(array('route' => 'events.store','method'=>'POST','files'=>'true')) !!}
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          @if (Session::has('success'))
          <div class="alert alert-success">{{ Session::get('success') }}</div>
          @elseif (Session::has('warnning'))
          <div class="alert alert-danger">{{ Session::get('warnning') }}</div>
          @endif

        </div>

        <div class="col-xs-4 col-sm-4 col-md-6">
          <div class="form-group">
            {!! Form::label('event_name','Event Name:') !!}
            <div class="">
              {!! Form::text('event_name', null, ['class' => 'form-control']) !!} {!! $errors->first('event_name', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('start_date','Start Date:') !!}
            <div class="">
              {!! Form::date('start_date', null, ['class' => 'form-control']) !!} {!! $errors->first('start_date', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('end_date','End Date:') !!}
            <div class="">
              {!! Form::date('end_date', null, ['class' => 'form-control']) !!} {!! $errors->first('end_date', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('start_time','Start Time:') !!}
            <div class="">
              {!! Form::time('start_time', null, ['class' => 'form-control']) !!} {!! $errors->first('start_time', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('end_time','End Time:') !!}
            <div class="">
              {!! Form::time('end_time', null, ['class' => 'form-control']) !!} {!! $errors->first('end_time', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('category_id','Category:') !!}
            <div>
              {!! Form::select('category_id', $categoriesArray, ['class' => 'selectpicker']) !!}
            </div>
          </div>
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2 offset-md-1 text-center add-event-submit-wrapper">
          {!! Form::submit('Add Event',['class'=>'btn btn-primary']) !!}
        </div>
      </div>
      {!! Form::close() !!}

    </div>
    <div class="col-md-8">
      {!! $calendar->calendar() !!}
    </div>
  </div>
</div>
{!! $calendar->script() !!} @endsection