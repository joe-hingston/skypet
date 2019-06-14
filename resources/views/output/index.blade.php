@extends('layouts.mainlayout')

@section('content')

    <div class="container">
        <div class="input-group">
                   <input type="hidden" name="search_param" value="all" id="search_param">
            <input type="text" class="form-control" name="x" placeholder="Search term...">
            <div class="input-group-btn search-panel">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span id="search_concept">Filter by</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#DOI">DOI</a></li>
                    <li><a href="#its_equal">It's equal</a></li>
                    <li><a href="#greather_than">Greather than ></a></li>
                    <li><a href="#less_than">Less than < </a></li>
                    <li class="divider"></li>
                    <li><a href="#all">Anything</a></li>
                </ul>
            </div>
            <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                </span>
        </div>





        <div class="panel panel-primary">
                <div class="panel-heading">Articles <small> ({{ $Outputs->count() }}) </small></div>



                <div class="row">
                    <div class="container">
                        <form action="{{ url('/output/search') }}" method="get">
                            <div class="form-group">
                                <input
                                    type="text"
                                    name="q"
                                    class="form-control"
                                    placeholder="Search all fields..."
                                    value="{{ request('q') }}"
                                />
                            </div>
                        </form>
                    </div>

                    <div class="container">
                        <form action="{{ url('/output/search') }}" method="get">
                            <div class="form-group">
                                <input
                                    type="text"
                                    name="w"
                                    class="form-control"
                                    placeholder="Search DOIs..."
                                    value="{{ request('w') }}"
                                />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">

                <div class="panel-body">

                    @forelse ($Outputs as $Output)
                        <article>
                            <h2>{{ $Output->title }}</h2>

                            <p>{{ $Output->abstract }}</p></body>


                        </article>
                    @empty
                        <p>No articles found</p>
                    @endforelse
                </div>
            </div>
            </div>

    </div>


@endsection
