@if(!$users->isEmpty())

<p class="float-right"> Displaying {{$users->firstItem()}}-{{ $users->lastItem() }} of {{$users->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$users->total()}}  Records</p>
@endif
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Card</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$users->isEmpty())
            @foreach($users as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td>{{$list->name}}</td>
                <td>{{$list->email}}</td>
                <td>{{$list->contact}}</td>
                <td class="text-capitalize">{{$list->card_number}}</td>
                <td>@if($list->active == '1') {{'Active'}} @else {{'Inactive'}} @endif</td>
                <td class="actions" data-th="">
               @if($list->type == "user" && $list->id != $user_id)
               <button class="btn badge-info btn-xs addUserToCompany" data-cid="{{$user_id}}" data-id="{{en_de_crypt($list->id,'e')}}"><i class="mdi mdi-account-plus"></i></button>
               @endif
              </td>
           </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No User Found..</td></tr>
        @endif
</tbody>
</table>
  {!! $users->onEachSide(1)->links('ajax_pagination') !!}
</div>
