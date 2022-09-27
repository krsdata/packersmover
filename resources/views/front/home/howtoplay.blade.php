
@extends('front/layouts/main')

@section('content')

   

        <!-- blog details begin -->
        <div class="blog-details">
            <div class="container">
                <div class="part-post">
                    <div class="part-img">
                        <img src="assets/img/news/blog-details.jpg" alt="">
                    </div>
                    <div class="part-text">
                        <h2 class="title">How To Play</h2>
                      <ol>
                          @foreach($draw as $dkey => $dval)
                          <li>
                              {{$dval->description}}
                          </li>
                          @endforeach
                      </ol>
                    </div>
                </div>
              
     

        @endsection