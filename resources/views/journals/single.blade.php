@extends('layouts.mainlayout')

@section('content')


    <div class="container">

        <ul class="list-group">
            <li class="list-group-item active">{{$journal->title}}</li>
            <li class="list-group-item">Publisher: {{$journal->publisher}}</li>
            <li class="list-group-item">Print ISSN: {{$journal->issn}}</li>
            <li class="list-group-item">Electronic ISSN: {{$journal->eissn}}</li>
            <li class="list-group-item">Total Dois: {{$journal->total_articles}}</li>
        </ul>
    </div>


@endsection
