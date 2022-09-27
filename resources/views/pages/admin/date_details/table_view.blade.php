<div class="col-sm-12 px-2" style="display:inline-block;">@if(!$datas->isEmpty())<p class="float-right">  {{ __("a.Displaying") }} {{$datas->firstItem()}}-{{ $datas->lastItem() }}  {{ __("a.of") }} {{$datas->total()}}  {{ __("a.Records") }}</p>@else
                    <p class="float-right">  {{ __("a.Displaying") }} {{" 0 "}}-{{ "0" }}  {{ __("a.of") }}  {{$datas->total()}} {{ __("a.Records") }} </p>
                    @endif
                  </div>
                  <div class="grid-views-sections">
                    <div class="row m-0">
                      @if(!$datas->isEmpty())
                      @foreach($datas as $list)
                      <div class="col-sm-3" id="{{en_de_crypt($list->id,"e")}}">
                        <div class="card-body text-center company-listing-gridview">
                        @if(isset($columns)) 
                        @foreach ($columns as $column)
                        @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                        <h4 style="font-weight: normal;"> {{$column}}: {{$list->$column}}</h4>
                        @endif
                        @endforeach
                        @endif
                          <div class="vp-company-buttonsview">
                            <a href="{{url('/admin/date_details/edit/' . en_de_crypt($list->id,'e') ) }}"><button class="btn btn-primary btn-xs"><span class="cust-box-icon-add"> <i class="la la-pencil-alt  cust-box-icon"></i>  </span> </button></a>
                            <button class="btn btn-danger btn-xs delete" data-id="{{en_de_crypt($list->id ,'e')}}" data-model="Transaction"><span class="cust-box-icon-add"> <i class="la la-trash-alt  cust-box-icon"></i> </span> </button>
                          </div>
                        </div>
                      </div>
                      @endforeach
                      {!! $datas->onEachSide(1)->links("ajax_pagination") !!}
                      @else
                      <div class="card card-inverse-info col-md-12" id="context-menu-simple">
                        <div class="card-body">
                          <p class="card-text"> {{ __("a.No_Data_Found") }}  </p>
                        </div>
                      </div>
                      @endif
                    </div>
                  </div>
                  
                  <div class="table_add_class">
                    @if(!$datas->isEmpty())
                    <div class=" table-responsive">
                      <table class="rwd-tables table table-striped">
                        <thead>
                        <tr>
                        @if(isset($columns)) 
                        @foreach ($columns as $column)
                        @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                          <th> {{$column}}  </th>
                        @endif
                        @endforeach
                        @endif  
                        <th> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach($datas as $list)
                          <tr id="{{en_de_crypt($list->id,"e")}}">
                          @if(isset($columns)) 
                          @foreach ($columns as $column)
                          @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                            <td>  <div >{{$list->$column}} </div> </td>
                          @endif
                          @endforeach
                          @endif
                            <td class="actions" data-th="">

                  
                              @if($list->created_by == $user_id)
                             
                              <!-- @endif -->
                            <a href="{{url("/admin/date_details/edit/" . en_de_crypt($list->id,"e") ) }}"><button class="btn btn-primary btn-xs"><span class="cust-box-icon-add"> <i class="la la-pencil-alt  cust-box-icon"></i>  </span> </button></a>
                            <button class="btn btn-danger btn-xs delete" data-id="{{en_de_crypt($list->id ,"e")}}" data-model="Transaction"><span class="cust-box-icon-add"> <i class="la la-trash-alt  cust-box-icon"></i> </span> </button>
                            

                  
                            </td>
                          </tr>  
                          @endforeach
                        </tbody>
                      </table>
                      {!! $datas->onEachSide(1)->links("ajax_pagination") !!}
                    </div>
                  
                    @else
                    <div class="row">
                      <div class="card card-inverse-info col-md-12" id="context-menu-simple">
                        <div class="card-body">
                          <p class="card-text"> {{ __("a.No_Data_Found") }} </p>
                        </div>
                      </div>
                    </div>
                    @endif
                  
                  </div>