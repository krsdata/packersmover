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
                              <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                  <div class="card-body">
                  
                                    <div class="row">
                                          <div class="col-sm-12">
                                              <div class="company-common-header_view col-sm-6">
                                              <span class="company-list-cust-box"> <i class="la la-money-bill-alt cust-box-icon"></i> </span>
                                              <h4 class="cust-card-title">

                                                Table List
                                              </h4>
                                              </div>
                                              <div class="mx-2 add-button-view_ffg  float-right">
                                                  <a href="{{route("date_details_create")}}" ><button class="btn btn-primary border-radius-05"><span class="cust-box-icon-add"> <i class="la la-plus cust-box-icon"></i> </span>  {{ __("a.Add") }} </button></a>



                                                date_details
                                              </h4>
                                              </div>
                                              <div class="mx-2 add-button-view_ffg  float-right">
                                                  <a href="{{route("date_details_create")}}" ><button class="btn btn-primary border-radius-05"><span class="cust-box-icon-add"> <i class="la la-plus cust-box-icon"></i> </span>  {{ __("a.Add") }} </button></a>

                                              </div>
                                              <div class="add-button-view_greedview float-right">
                                                  <ul>
                                                      <li class="active first-grid-view"> <span> <i class="mdi10 mdi-view-grid  la la-th-large menu-icon"></i></span> </li>
                                                      <li class="second-table-view"> <span> <i class="mdi10 mdi-view-sequential las la-list  menu-icon"></i></span> </li>
                                                  </ul>
                                              </div>
                  
                  
                                          </div>
                                          <div class="col-sm-12 mt-2">
                                              <div class="common-view-header-view-search px-2">
                  
                                                  <div class="m-0 add-button-view_search">
                                                          <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                                                                  {{ csrf_field() }}
                  
                                                      <div class="form-group d-flex custom-search-view-place">
                                                          <input value="{{$search_input}}" type="text" name="search_input" id="search_input" class="form-control" placeholder=" {{ __("a.Search_Subscription") }} " >
                                                          <button   class="btn btn-primary common_search_filter" ><i class="la la-search"></i></button>
                                                      </div>
                                                     </form>
                                                  </div>
                                              </div>
                                          </div>
                                        </div>
                                      <div id="table_filter_view">

                                          @include("pages.admin.date_details.table_view")

                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                  @endsection
                  @section("scripts")
                  <script type="text/javascript">
                  </script>
                  @stop
                  