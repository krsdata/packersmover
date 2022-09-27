@if(!$withdraw->isEmpty())

<p class="float-right"> Displaying {{$withdraw->firstItem()}}-{{ $withdraw->lastItem() }} of {{$withdraw->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$withdraw->total()}}  Records</p>
@endif

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
    <tr>
                <th>Id</th>
                <th>Acc No</th>
                <th>Branch Code</th>   
                <th>Amount</th>
                <th>Mobile</th>
                </tr>
    </thead>
    <tbody>
        @if(!$withdraw->isEmpty())
            @foreach($withdraw as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
            <td >{{$list->id}}</td>
                <td >{{$list->account_number}}</td>
                <td >{{$list->branch_code}}</td>
                <td >{{$list->amount	}}</td>
                <td >{{$list->contact	}}</td>                 
                </td> 
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Request Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $withdraw->onEachSide(1)->links('ajax_pagination') !!}
</div>