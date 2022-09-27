@if(!$privacy->isEmpty())

<p class="float-right"> Displaying {{$privacy->firstItem()}}-{{ $privacy->lastItem() }} of {{$privacy->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$privacy->total()}}  Records</p>
@endif

<br/>
<div class="row my-3 m-0">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card border-0 border-radius-2 bg-primary">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
            <div class="icon-rounded-inverse-primary  icon-rounded-xs">
                <i class="mdi mdi-file-outline"></i>
            </div>
            <div class="text-white ">
                <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">privacy Count </p>
                <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{$s_count}}</h3>
                <small class="mb-0">Count</small>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>    
</div>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$privacy->isEmpty())
            @foreach($privacy as $list)
            <tr id="{{en_de_crypt($list->id,'e')}}">        
                <td class="text-capitalize">{{$list->id}}</td>        
                <td class="text-capitalize">{{$list->name}}</td>
                <td class="text-capitalize">{{$list->created_at}}</td>
                <td class="actions" data-th="">
                  <a href="{{ url('/admin/privacy/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>
                </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No privacy Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $privacy->onEachSide(1)->links('ajax_pagination') !!}
</div>