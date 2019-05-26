@extends('layout.gs-inner')

@section('header')
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-8 col-lg-offset-2">
                        <h1>Search</h1>
                        <div class="inner-subtext">Enter the name of someone to search for and add it to the system.</div>
                        <form class="form-horizontal" method="POST" action="/search"> @csrf
                            <div class="form-group">
                                <label for="search-search" class="col-lg-2 control-label">Search</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="search-search" name="search-search"
                                           placeholder="name, title, or terms"/>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('refine')
    <section id="refine-results">

    </section>
@endsection

@section('content')
    <section id="results">
        <div id="search-results" class="row">
            <div class="col-lg-12 panel-list">
                @if(!empty($search))
                    <h2>Search results for query: <strong>{{ $searchQuery }}</strong></h2>

                    @foreach($search as $_type => $_typeList)
                        @if(!empty($_typeList))
                            @include('search-results-panel')
                        @else
                            <p>No <strong>{{ $_type }}</strong> matches</p>
                        @endif
                    @endforeach
                @else
                    <h2>No Results</h2>
                @endif
            </div>
        </div>
    </section>
@endsection

