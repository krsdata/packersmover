@extends("layouts.dashboard")
                    @section("dashcontent")
                    @php
                      $lang =  \Session::get("lang");
                      if(empty($lang)){
                        $lang = "en";
                      }
                      app()->setLocale($lang);
                      @endphp
                    <div class="row">
                      <div class="col-12 grid-margin">
                        <div class="card">
                          <div class="card-body">
                            <div class="transaction-common-header_view">
                              <span class="transaction-list-cust-box"> <i class="la la-money-bill-alt cust-box-icon"></i> </span>
                            <h4 class="cust-card-title"> @if(isset($datas))  Update date_details   @else  Add New date_details  @endif   </h4>
                            <span class="company-list-cust-back"> <a href=""> <i class="la la-arrow-left cust-box-icon float-right"></i> </a> </span>
                            </div>
                              <form id="addtrn" class="form-sample" method="POST" action = "{{route("date_details_stores")}}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    
                              <input type="hidden" name="id" id="id" value="@if(isset($datas)){{en_de_crypt($datas->id,"e")}}@endif">
                              <div class="col-sm-12">&nbsp;
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                @if(isset($columns)) 
                                @foreach ($columns as $column)
                                @if($column!="id" &&  $column!="created_at" && $column!="updated_at")
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"> {{$column}} </label>
                                    <div class="col-sm-8">
                                    <input type="text" name="{{$column}}" id="{{$column}}" class="form-control {{$column}}"
                                   data-msg-required="Required field" value="@if(isset($datas)){{$datas->$column}}@endif"  required />

                                    <label id="{{$column}}-error" class="error" for="{{$column}}"></label>
                                    <label id="{{$column}}-server" class="server-error" for="{{$column}}"></label>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @endif
                                </div>
                            </div>
                             <div class="row">
                                    <div class="col-md-12">
                                      <button type="reset" value="reset" id="create_action_reset" style="display:none"></button>
                                      <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> @if(!isset($data->id)) {{ __("a.Submit") }} @else  {{ __("a.Update") }} @endif </button>
                                    </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <script type="text/javascript">
                    $(document).ready(function () {
                        
                    })
                    </script>
                    @endsection 