@if(!$obj->isEmpty())

<p class="float-right"> Displaying {{$obj->firstItem()}}-{{ $obj->lastItem() }} of {{$obj->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$obj->total()}}  Records</p>
@endif
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Folder Name</th>
        <th>Title</th>
        <th>Currency</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$obj->isEmpty())
            @foreach($obj as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td>{{$list->name}}</td>
                <td class="text-capitalize">{{$list->title}}</td>
                <td class="text-capitalize">{{$list->currency_code}}</td>
                <td>@if($list->active == '1') {{'Active'}} @else {{'Inactive'}} @endif</td>
                <td class="actions" data-th="">
                <a href="{{ url('/admin/station/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                @role('admin') 
                <a href="#" class="station_delete" data-id={{en_de_crypt($list->id,'e')}} data-model="Stations"><button class="btn badge-primary btn-xs"><i class="mdi mdi-delete-circle"></i></button></a>
                @endrole
                </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No User Found..</td></tr>
        @endif
</tbody>
</table>
  {!! $obj->onEachSide(1)->links('ajax_pagination') !!}
</div>
<script type="text/javascript">
    $('.station_delete').on('click', function(event) {
        var id=$(this).data("id");
        var model=$(this).data("model");
        swal({
            title: 'Are you sure?',
            text: "It will permanently deleted !",
            type: 'warning',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes!",
            showCancelButton: true,
        })
        .then((willDelete) => {
           if (willDelete.value == true) {
              if(id){
                   $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': _token
                      }
                  });
                  $.ajax({
                      url: APP_URL + '/admin/common/delete',
                      method: 'POST',
                      data: {"id":id,"model":model},
                      success: function (data) {
                         if(data.msg_type=="success"){
                             swal(data.msg);
                             swal(
                              'Success',
                              data.msg,
                              'success'
                            )
                             location.reload();
                         }else{
                              swal(
                                  'Error!',
                                  data.msg,
                                  'error'
                              )
                            //location.reload();
                         }  
                      }
                  });
              }
          }else{
              swal("Cancelled", "Your Station  is safe :)", "error");
           }
        });
        
    });
</script>
