@if(!$customers->isEmpty())

<p class="float-right"> Displaying {{$customers->firstItem()}}-{{ $customers->lastItem() }} of {{$customers->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$customers->total()}}  Records</p>
@endif
<a class="btn btn-success" href="{{ route('exportUsers') }}">Export Users</a>
<a class="btn btn-success" href="{{ route('generate_pdf') }}">Export Pdf users</a>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Sr No.</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        
      </tr>
    </thead>
    <tbody>
        @if(!$customers->isEmpty())
        <?php $i=1;?>
            @foreach($customers as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td>{{$i++}}</td>
                <td>{{$list->name}} {{$list->last_name}}</td>
                <td>{{$list->email}}</td>
                <td>{{$list->contact}}</td>
           </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No User Found..</td></tr>
        @endif
</tbody>
</table>
  {!! $customers->onEachSide(1)->links('ajax_pagination') !!}
</div>
