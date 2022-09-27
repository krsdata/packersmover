@if(!$order->isEmpty())

<p class="float-right"> Displaying {{$order->firstItem()}}-{{ $order->lastItem() }} of {{$order->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$order->total()}}  Records</p>
@endif

<br/>


<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$order->isEmpty())
            @foreach($order as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td><button class="btn badge-primary btn-sm openTankStockDetails">{{$list->id}}</button></td>                
                <td class="text-capitalize">{{$list->name}}</td>
                <td class="text-capitalize">{{$list->email}}</td>
                <td class="text-capitalize">{{$list->contact}}</td>
                <td class="actions" data-th="">
                  <a href="{{ url('/admin/order/order_detail/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-eye"></i></button></a>                  
                  
                  <button class="btn badge-danger btn-xs"  name="remove_levels" value="delete" id="{{$list->id}}"><i class="mdi mdi-delete"></i></button>    
                  
                  <a class="btn btn-success" target="_blank" href="{{ url('/admin/order/generate_invoicepdf/' . en_de_crypt($list->id,'e')) }}">Pdf</a>

                  <form method="GET" action="{{ url('/admin/order/order_delete/' . en_de_crypt($list->id,'e') ) }}" accept-charset="UTF-8" id="deleteForm_{{$list->id}}">  
                  
                  <input type="hidden" name="remove_levels" value="delete" id="{{$list->id}}">

                  </form>

                </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Expense Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $order->onEachSide(1)->links('ajax_pagination') !!}
</div>

