@extends('layouts.dashboard')
@section('dashcontent')

 

            <div class="row">
                <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Customer Details</div>
						</div>
						<div class="card-body">
							<dl class="row">
								<dd class="col-sm-4">Name</dd>
								<dt class="col-sm-8">{{$order_data->name}}</dt>
								<dd class="col-sm-4">Email</dd>
								<dt class="col-sm-8">{{$order_data->email}}</dt>
							</dl>
							<dl class="row">
								
							</dl>
							<dl class="row">
								<dd class="col-sm-4">Contact</dd>
								<dt class="col-sm-8">{{$order_data->contact}}</dt>
								<dd class="col-sm-4">Date</dd>
								<dt class="col-sm-8">{{$order_data->date}}</dt>
							</dl>
							
							<hr>
							   
							</div>
						</div>
			        </div>

                    <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Enquiry Details</div>
						</div>
						<div class="card-body">
							<dl class="row">
								<dd class="col-sm-4">Origin</dd>
								<dt class="col-sm-8">{{$order_data->origin}}</dt>
								<dd class="col-sm-4">Origin Floor</dd>
								<dt class="col-sm-8">{{$order_data->origin_floor}}</dt>
							</dl>
							<dl class="row">
								
							</dl>
							<dl class="row">
								<dd class="col-sm-4">Destination</dd>
								<dt class="col-sm-8">{{$order_data->destination}}</dt>
								<dd class="col-sm-4">Destination Floor</dd>
								<dt class="col-sm-8">{{$order_data->destination_floor}}</dt>
							</dl>
							
							<hr>
							   
							</div>
						</div>
			        </div>

            </div>
    
            
            <div class="row">
                <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Order Details</div>
							
						</div>
						<div class="card-body">
							
                            @foreach($items as $ikey => $ival)
							
						
							<dl class="row">
                               
                                <dd class="col-sm-4">{{$ival['name']}}</dd>
								<dd class="col-sm-4">
								{{$ival['wraps']}}
								</dd>
								<dt class="col-sm-4">{{$ival['qty']}}</dt>
								
							</dl>
							
							@endforeach
                            
							
							<hr>
							    
							</div>
						</div>
			        </div>

                    <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Order Total</div>
							
						</div>
						<div class="card-body">
							<dl class="row">
								<dd class="col-sm-3">Packing</dd>
								<dt class="col-sm-3">{{$order_data->packing}}</dt>
								<dd class="col-sm-3">Transport</dd>
								<dt class="col-sm-3">{{$order_data->transport}}</dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Loading</dd>
								<dt class="col-sm-3">{{$order_data->loading}}</dt>
								<dd class="col-sm-3">Unloading</dd>
								<dt class="col-sm-3">{{$order_data->unloading}}</dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">UnPacking</dd>
								<dt class="col-sm-3">{{$order_data->unpacking}}</dt>
								<dd class="col-sm-3">Ac</dd>
								<dt class="col-sm-3">{{$order_data->ac}}</dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Local</dd>
								<dt class="col-sm-3">{{$order_data->local}}</dt>
								<dd class="col-sm-3">Car Transport</dd>
								<dt class="col-sm-3">{{$order_data->car_transport}}</dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Insurance</dd>
								<dt class="col-sm-3">{{$order_data->insurance}}</dt>
								<dd class="col-sm-3">GST</dd>
								<dt class="col-sm-3">{{$order_data->gst}}</dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Discount</dd>
								<dt class="col-sm-3">{{$order_data->discount}}</dt>
								<dd class="col-sm-3">Transport gst</dd>
								<dt class="col-sm-3">{{$order_data->transport_gst}}</dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Sub Total</dd>
								<dt class="col-sm-3">{{$order_data->sub_total}}</dt>
							</dl>
							
							<hr>
							    
							</div>
						</div>
			        </div>
                    
            </div>
				
			<!-- End of Order Details -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> 
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
@endsection
