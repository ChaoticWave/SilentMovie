@extends('layout.grayscale')

<?php
if (!isset($search))
    $search = null;
?>
@section('content')
    <!-- About Section -->
    <section id="about" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-offset-2 col-lg-8 col-lg-offset-2">
                <h2>Silent Movie</h2>
                <p>Silent Movie is a small web application that allows you to analyze media data. The app knows how to talk to various online media systems to
                    retrieve pertinent details. This information is then stored in an <a href="https://www.elastic.co/webinars/introduction-elk-stack"
                                                                                         target="_blank">Elastic Stack</a> for analysis. How you analyze the
                    data is up to you. This
                    application just gets it there.</p>
                <p>Silent Movie is completely free, open source, and can be used for any purpose. <br /><br />
                    The full source code is freely available on GitHub at
                    <a href="https://github.com/ChaoticWave/SilentMovie/" target="_blank">https://github.com/ChaoticWave/SilentMovie/</a></p>
            </div>
        </div>
    </section>

    <!-- Lookup Section -->
    <section id="search-add" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-offset-2 col-lg-8 col-lg-offset-2">
                <h2>Search</h2>
                <p>Enter the name of someone to add to the system</p>
                <form class="form-horizontal" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="search-person" class="col-lg-2 control-label">Person</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="search-person" name="search-person" placeholder="name, title, or terms">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Go</button>
                </form>
            </div>
        </div>
        @if($search)
            <div class="row">
                <h2>Results</h2>
                @foreach($search as $_type => $_typeList)
                    <h3>{{ print_r($_type,true) }}</h3>
                    <table class="table table-bordered table-responsive results">
                        <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($_typeList as $_item)
                            @foreach($_item as $_key => $_value )
                                <tr>
                                    <td>{{ $_key }}</td>
                                    <td>{{ $_value }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        @endif
    </section>
@endsection