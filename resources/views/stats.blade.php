@extends('layouts.app') @section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Statistics</div>
        <div class="card-body">
          <h2>{{ $title }}</h2>
          <div id="chart-div"></div>
          @piechart('chart', 'chart-div')
        </div>
      </div>
    </div>
  </div>
  @endsection