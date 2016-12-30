@extends('layout.gs-inner')

@section('header')
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-8 col-lg-offset-2">
                        <h1>Search</h1>
                        <div class="inner-subtext">Enter the name of someone to search for and add it to the system.</div>
                        <form class="form-horizontal" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="search-person" class="col-lg-2 control-label">Person</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="search-person" name="search-person" placeholder="name, title, or terms">
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

@section('content')
    @if(!empty($search))
        <div id="search-results" class="row">
            <div class="col-lg-12">
                <h2>Search results for query: <strong>{{ $searchQuery }}</strong></h2>

                @foreach($search as $_type => $_typeList)
                    @if(!empty($_typeList))
                        @include('search-results-panel')
                    @else
                        <p>No <strong>{{ $_type }}</strong> matches</p>
                    @endif
                @endforeach

            </div>
        </div>
    @endif
@endsection

