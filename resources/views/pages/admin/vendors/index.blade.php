@extends('layouts.dashboard')
@section('dashcontent')

 <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                  <div class="col-sm-6">
                      <h4 class="cust-card-title">
                        <i class="mdi  mdi-account cust-box-icon"></i>
                        Vendor list
                      </h4>
                  </div>
                  <div class="col-sm-2">
                      <a href="{{route('vendors_create')}}" ><button class="btn btn-primary border-radius-05">Add Vendor</button></a>
                  </div>
                  <div class="col-sm-4">
                  <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                                {{ csrf_field() }}
                              
                    <div class="form-group d-flex custom-search-view-place">
                        <input value="{{$search_input}}" type="text" name="search_input" id="search_input" class="form-control" placeholder="Search Here...." >
                        <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify"></i></button>
                    </div>
                   </form>
                  </div>
                  </div>
                    <div id="table_filter_view">
                        @include('pages.admin.vendors.table_view')
                    </div>
                </div>
              </div>
            </div>
          </div>
@endsection
@section('scripts')
<script type="text/javascript">
</script>
@stop
