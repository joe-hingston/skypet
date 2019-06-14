@extends('home')

@section('title', 'Posts')

@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Service</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Horizon</td>
            <td>{{$horizon= '1'? "✔️" : ❌}}</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Elastic Search</td>
            <td>{{$elasticsearch = '1'? "✔️" : ❌}}</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Redis</td>
            <td>{{$redis = '1'? "✔️" : ❌}}</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Outputs</td>
            <td>{{$output}}</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Journals</td>
            <td>{{$journal}}</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Database</td>
            <td>{{$database}}</td>
        </tr>
        </tbody>
    </table>
@endsection
