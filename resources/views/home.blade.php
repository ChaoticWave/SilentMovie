@extends('layout.grayscale')

@section('content')
    <!-- About Section -->
    <section id="about" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-offset-2 col-lg-8 col-lg-offset-2">
                <h2><i class="fa fa-film"></i> Silent Movie</h2>
                <p>Silent Movie is a itsy bitsy web application that allows you to analyze media data. The app knows how to talk to
                    various online media systems
                    to
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
@endsection