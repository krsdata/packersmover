@extends('front/layouts/main')

@section('content')


<div class="games game-page">
            <div class="container">                                   
                <div class="row">                
                    <div class="col-xl-12 col-lg-12 col-md-8">
                        <div class="all-games anim-change">
                            <div class="title-cover">
                                <h4 class="games-title"> Select Ticket</h4>
                            </div>
                            <div class="row miscoo-row">

                                @foreach($monthly as $mkey => $mval)
                                <div class="col-xl-3 col-lg-4 col-sm-6" onclick="location.href='{{url('draw_register')}}'">
                                   <div class="single-game draw_form" data-id="{{$mval->id}}">
                                        <div class="part-img">
                                            <img src="assets/img/games/assets-1.jpg" alt="">
                                            <img class="icon-img" src="assets/img/games/icon-1.png" alt="">
                                        </div>
                                        <div class="part-text">
                                            <h4 class="game-title">
                                                {{$mval->ticket_name}}
                                            </h4>
                                            <p>{{$mval->description}} <br/>
                                            {{$mval->fee}} $ <br/>
                                            {{$mval->id}}
                                            </p>
                                            
                                            <a href="#0" class="def-btn def-small">Play Now</a>
                                        </div>
                                    </div>
                                    
                                </div>
                                @endforeach
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection