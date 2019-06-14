@extends('layouts.mainlayout')

@section('content')


    <div class="container">

        {!! Form::open(['route' => 'journals.store']) !!}
        {!! Form::submit(' Add ISSN ', ['class' => 'btn btn-success mb-2 float-right']) !!}
        <div class="form-group form-inline float-right">

            {!! Form::text('issn', null, ['class' => 'form-control']) !!}


        </div>

        {!! Form::close() !!}




    </div>

        <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Articles <small> ({{ $Journals->count() }}) </small></div>


            <div class="row" id="journallist">


                    @forelse ($Journals as $Journal)
                    <div class=""> <a href="journals/{{$Journal->id}}"><h2>{{ $Journal->title }}</h2> </a></div>
                    @empty
                        <p>No articles found</p>
                    @endforelse

            </div>
            </div>
        </div>


@endsection
