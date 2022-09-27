@if(!$category->isEmpty())

<p class="float-right"> Displaying {{$category->firstItem()}}-{{ $category->lastItem() }} of {{$category->total()}}  Records</p>
@else
<p class="float-right"> Displaying {{' 0 '}}-{{ ' 0 ' }} of {{$category->total()}}  Records</p>
@endif
<br/>


<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Category </th>
        <th>Sub Category </th>
        <th>Image</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        @if(!$category->isEmpty())
            @foreach($category as $list)
             @if(!isset($main_cat[$list->parent_id]))
              <?php continue; ?>
             @endif

             <?php 
              
             // $main_cat_name = $main_cat[$list->parent_id]??$list->name;
 
              
              ?> 
            <tr id="{{en_de_crypt($list->id,'e')}}">
                <td><button class="sid-name-text-st-sm btn badge-primary btn-sm openTankStockDetails">{{$list->id}}</button></td>
                <td class="text-capitalize">{{$main_cat[$list->parent_id]??'NA'}}</td>

                <td class="text-capitalize">{{$list->name}}</td>
                <td class="text-capitalize"><img src="{{asset('images/category')}}/{{$list->image}}"></td>
                <td class="text-capitalize">{{$list->created_at}}</td>
                <td class="actions" data-th="">
                  <a href="{{ url('/admin/category/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn badge-primary btn-xs"><i class="mdi mdi-lead-pencil"></i></button></a>                  
                  
                  <button class="btn badge-danger btn-xs"  name="remove_levels" value="delete" id="{{$list->id}}"><i class="mdi mdi-delete"></i></button>    
                  
                  <form method="GET" action="{{ url('/admin/category-list?' . en_de_crypt($list->id,'e') ) }}" accept-charset="UTF-8" id="deleteForm_{{$list->id}}">  
                  
                  <input type="hidden" name="remove_levels" value="delete" id="{{$list->id}}">
                  <input type="hidden" name="id" value="{{en_de_crypt($list->id,'e')}}">

                  </form>
                </td>
            </tr>
           @endforeach
        @else
        <tr><td class="server-error" colspan="6">No Order Found..</td></tr>
        @endif
    </tbody>
  </table>
  {!! $category->onEachSide(1)->links('ajax_pagination') !!}
</div>
