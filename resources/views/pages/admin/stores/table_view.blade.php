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
        <th>Type</th>
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
                <td class="text-capitalize">{{str_replace("-"," ",$list->type)}}</td>
                <td>@if($list->active == '1') {{'Active'}} @else {{'Inactive'}} @endif</td>
                <td class="actions" data-th="">
                {{-- <a target="_blank" href="mailto:{{$list->email}}" ><button class="btn badge-primary btn-sm"><i class="fa fa-envelope"></i></button></a> --}}
               @if($list->type != "admin" && $list->id != $user_id)
                <a href="{{ url('/admin/user/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                <button class="btn btn-danger btn-xs delete" data-id="{{en_de_crypt($list->id ,'e')}}" data-model="User"><i class="mdi mdi-delete"></i></button>
               @endif
               @if($list->type == "company" && $list->id != $user_id)
               <a href="{{ url('/admin/company-user/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-info btn-xs"><i class="mdi mdi-account-plus"></i></button></a>
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
