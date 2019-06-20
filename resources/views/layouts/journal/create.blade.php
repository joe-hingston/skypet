@extends('layouts.mainlayout')

@section('content')

    <div class="container">


        <ul class="list-group">
            <li class="list-group-item active">{{$title}}</li>
            <li class="list-group-item">Publisher: {{$publisher}}</li>
            <li class="list-group-item">Print ISSN: {{$issn}}</li>
            <li class="list-group-item">Electronic ISSN: {{$eissn}}</li>
            <li class="list-group-item">Total Dois: {{$total_articles}}</li>
          </ul>


        <div class="alert alert-success center-block">
            <strong>Success!</strong> The ISSN ({{$issn}}) exists on the CrossRef API and has been added for processing.
        </div>
    </div>


@endsection
