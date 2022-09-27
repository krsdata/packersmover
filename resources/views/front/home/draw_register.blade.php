
@extends('front/layouts/main')

@section('content')

        <!-- register begin -->
        <div class="register">
            <div class="container">
                <div class="reg-body">
                <form  class="form-sample" method="POST" action = "{{ url('contact_store') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <h4 class="sub-title">Register For Draw</h4>
                        <div class="row">
                        
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <input type="text" placeholder="First Name*" required>
                            </div>

                               <div class="col-xl-6 col-lg-6 col-md-6">
                                <input type="text" placeholder="Last Name*" required>
                                </div>

                                 <div class="col-xl-6 col-lg-6 col-md-6">
                                <select name="gender" id="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                </select>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6">
                                <select name="country" id="country" class="form-control" required>
                                <option value="0">Select Country</option>
                                @foreach($country as $clist)
                                <option value="{{$clist->id}}">{{$clist->name}}</option>
                                @endforeach
                                </select>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6">
                                <select name="state" id="state" class="form-control" required>
                                <option value="0">Select State</option>
                                </select>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6">
                                <select name="city" id="city" class="form-control" required>
                                <option value="0">Select City</option>
                                </select>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6">            
                                <input type="text" placeholder="Phone No:*" required>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6">            
                                <input type="text" placeholder="OTP:*" required>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                        <div class="row">                            
                            <div class="col-xl-6 col-lg-6">
                                <button type="submit" class="def-btn btn-form">Submit <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- register end -->
 @endsection

 <script src="{{ asset('front/js/jquery.js') }}"></script>
 <script>
$(document).ready(function(){

     $("#country").change(function(){
        var id = $(this).val();
        if(id == 0)
        {
            alert("Please Select Country..");
            return false;
        }
                $.ajax({
                type:"GEt",
                headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: APP_URL +"/get_states",
                data: {"id":id},
               
                success: function(res){
                    var data = $.parseJSON(res);
                    $(data).each(function (i, val) {
                    
                       $("#state").append("<option value="+val.id+">"+val.name+"</option>");
                    }); 

                }
            });

     })

     $("#state").change(function(){
        var id = $(this).val();

                $.ajax({
                type:"GEt",
                headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: APP_URL +"/get_city",
                data: {"id":id},
               
                success: function(res){
                    var data = $.parseJSON(res);
                    $(data).each(function (i, val) {
                       
                       $("#city").append("<option value="+val.id+">"+val.name+"</option>");
                    }); 

                }
            });

     })
    
})

 </script>