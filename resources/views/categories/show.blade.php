@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Category {{ $category->id }}</div>
                <div class="card-body">

                    <a href="{{ url('/categories') }}" title="Back">
                        <button class="btn btn-warning btn-sm">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
                    </a>
                    <a href="{{ url('/categories/' . $category->id . '/edit') }}" title="Edit Category">
                        <button class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>
                    </a>

                    <form method="POST" action="{{ url('categories' . '/' . $category->id) }}" accept-charset="UTF-8" style="display:inline">
                        {{ method_field('DELETE') }} {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Category" onclick="return confirm(&quot;Confirm delete?&quot;)">
                            <i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                    </form>
                    <br/>
                    <br/>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <th> Title </th>
                                    <td> {{ $category->title }} </td>
                                </tr>
                                <tr>
                                    <th> Color </th>
                                    <td> {{ $category->color }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection