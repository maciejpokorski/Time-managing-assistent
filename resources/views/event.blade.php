@extends('layouts.app') @section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      {!! Form::open(array('route' => ['events.update', $event->id], 'method'=>'PUT')) !!}
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
              {!! Form::text('event_name', $event->title, ['class' => 'form-control']) !!} {!! $errors->first('event_name', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('start_date','Start Date:') !!}
            <div class="">
              {!! Form::date('start_date', $event->start_date, ['class' => 'form-control']) !!} {!! $errors->first('start_date', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('end_date','End Date:') !!}
            <div class="">
              {!! Form::date('end_date', $event->end_date, ['class' => 'form-control']) !!} {!! $errors->first('end_date', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('start_time','Start Time:') !!}
            <div class="">
              {!! Form::time('start_time', $event->start_time, ['class' => 'form-control']) !!} {!! $errors->first('start_time', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2">
          <div class="form-group">
            {!! Form::label('category_id','Category:') !!}
            <div>
              <select style="background: {{ reset($categoriesArray)['color'] }}" onload="alert('xd')" onchange="this.style.background = this.options[this.selectedIndex].style.background"
                class="form-control selectpicker" name="category_id">
                @foreach($categoriesArray as $key => $category)
                <option class="with-color" style="background: {{ $category['color'] }}" value="{{ $key }}">
                  {{ $category['title'] }}
                </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3">
          <div class="form-group">
            {!! Form::label('end_time','End Time:') !!}
            <div class="">
              {!! Form::time('end_time', $event->end_time, ['class' => 'form-control']) !!} {!! $errors->first('end_time', '
              <p class="alert alert-danger">:message</p>') !!}
            </div>
          </div>
        </div>


        <div class="col-xs-1 col-sm-1 col-md-2 text-center add-event-submit-wrapper">
          {!! Form::submit('Update Event',['class'=>'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}

        <div class="col-xs-2 col-sm-2 col-md-2 text-center add-event-submit-wrapper">
          {!! Form::open(array('route' => ['events.destroy', $event->id], 'method'=>'DELETE')) !!} {{ Form::submit('Delete', array('class'
          => 'btn btn-danger')) }} {{ Form::close() }}
        </div>

      </div>

    </div>
  </div>
</div>
@endsection