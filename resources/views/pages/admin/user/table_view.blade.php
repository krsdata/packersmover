@if(!$users->isEmpty())

<p class="float-right"> Displaying {{$users->firstItem()}}-{{ $users->lastItem() }} of {{$users->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$users->total()}}  Records</p>
@endif
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$users->isEmpty())
            @foreach($users as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td>{{$list->name}}</td>
                <td>{{$list->last_name}}</td>
                <td>{{$list->email}}</td>
                <td>{{$list->contact}}</td>
                
                <td class="actions" data-th="">
                
               @if(Auth::user()->type == "sai-manager")
               <a href="{{ url('/admin/user/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                <button class="btn badge-danger btn-xs"  name="remove_levels" value="delete" id="{{$list->id}}"><i class="mdi mdi-delete"></i></button>    
                  
                  <form method="GET" action="{{ url('/admin/user-list?' . en_de_crypt($list->id,'e') ) }}" accept-charset="UTF-8" id="deleteForm_{{$list->id}}">  
                  
                  <input type="hidden" name="remove_levels" value="delete" id="{{$list->id}}">
                  <input type="hidden" name="id" value="{{en_de_crypt($list->id,'e')}}">

                  </form>
               @elseif($list->type != "admin" && $list->id != $user_id)
               <a href="{{ url('/admin/user/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
               
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
