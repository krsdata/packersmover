@if(!$contact->isEmpty())

<p class="float-right"> Displaying {{$contact->firstItem()}}-{{ $contact->lastItem() }} of {{$contact->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$contact->total()}}  Records</p>
@endif

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>              
                <th>Id</th>
                <th>Name</th>
                <th>Email Id</th>
                <th>Mobile</th>
                <th>Message</th>
                <th>Type</th>
                <th>Created At</th>
                </tr>
    </thead>
    <tbody>
        @if(!$contact->isEmpty())
            @foreach($contact as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
            <td >{{$list->id}}</td>
                <td >{{$list->name}}</td>
                <td >{{$list->email_id}}</td>
                <td >{{$list->mobile_no}}</td>
                <td >{{$list->message	}}</td>
                <td >{{$list->type	}}</td>               
                <td >{{$list->created_at	}}</td>                 
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Contact Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $contact->onEachSide(1)->links('ajax_pagination') !!}
</div>