@extends('front/layouts/main')

@section('content')

        <!-- account begin -->
        <div class="user-dashboard">
            <div class="container">

                <div class="dashboard-menu">
                    <nav class="navbar navbar-expand-lg">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>
                      
                        <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Deposit <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Exchange</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Withdraw</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Rewards</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Contests</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Settings</a>
                                </li>
                            </ul>
                        </div>
                      </nav>
                </div>

                <div class="user-statics">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">


                        <form name="user_update" id="user_update" method="POST" action = "{{url('store_profile')}}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" >
                            {{ csrf_field() }}
                        <input type="hidden" name="user_id" id="user_id" value="{{en_de_crypt($user->id,'e')}}">
                        
                        <input type="text" placeholder="First Name*" name="name" value="<?php echo $user->name; ?>" required>

                        <input type="text" placeholder="Last Name*" name="last_name" value="<?php echo $user->last_name; ?>" required>

                        <input type="text" placeholder="Email*" name="email" value="<?php echo $user->email; ?>" required>
                        
                        <input type="text" placeholder="Contact*" name="contact" value="<?php echo $user->contact; ?>" required>
                    

                        <select name="gender" id="gender" class="form-control" style="height: 60px;width: 100%;">
                        <option value="">Select Gender</option>
                        <option value="male" {{ (isset($user->gender) == 'male') ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ (isset($user->gender) == 'female') ? 'selected' : '' }}>Female</option>
                        </select>

                        <select name="country" id="country" class="form-control" style="height: 60px;width: 100%;" required>
                            <option value="0">Select Country</option>
                            @foreach($country as $clist)
                            <option {{ (isset($user->country) == $clist->name) ? 'selected' : '' }} value="{{$clist->name}}" data-id="{{$clist->id}}">{{$clist->name}}</option>
                            @endforeach
                        </select>

                        <select name="state" id="state" class="form-control" style="height: 60px;width: 100%;" required>
                        <option value="0">Select State</option>
                        </select>

                        </form>

                    </div>
                      
                     
                       
                       
                       
                        
                    </div>
                </div>

                

                
            </div>
        </div>
        <!-- account end -->

@endsection