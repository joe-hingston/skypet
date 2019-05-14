@extends('layouts.mainlayout')

@section('content')

    <div class="container">


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
                                    placeholder="Search..."
                                    value="{{ request('q') }}"
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
