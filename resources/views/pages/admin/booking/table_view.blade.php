@if(!$bookingdraw->isEmpty())

<p class="float-right"> Displaying {{$bookingdraw->firstItem()}}-{{ $bookingdraw->lastItem() }} of {{$bookingdraw->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$bookingdraw->total()}}  Records</p>
@endif

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>
                <th>Booking Id</th>
                <th>Booking Type</th>
                <th>First Name</th>
                <th>Country</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Booking Status</th>
                <th>Action</th>
                </tr>
    </thead>
    <tbody>
        @if(!$bookingdraw->isEmpty())
            @foreach($bookingdraw as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
            <td >{{$list->id}}</td>
                <td >{{$list->type}}</td>
                <td >{{$list->first_name}}</td>
                <td >{{$list->country}}</td>
                <td >{{$list->city	}}</td>
                <td >{{$list->mobile	}}</td>               
                <td >{{$list->order_status	}}</td>
                 <td><a href="{{ url('/bookingview/' .$list->id) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-display"></i></button></a>
                </td> 
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Booking Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $bookingdraw->onEachSide(1)->links('ajax_pagination') !!}
</div>