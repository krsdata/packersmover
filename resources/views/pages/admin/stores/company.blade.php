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
            {{$user_data->name}} : User list
            </h4>
        </div>
        <div class="col-sm-6 ">
          <button class="btn badge-info btn-xs company_search_btn" data-toggle="modal" data-target="#myModalCompanyUser" style="float:right"><i class="mdi mdi-account-plus"></i></button>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-sm-12">
      <input type="hidden" id="loadmorecount" data-url="{{ route('admin_list') }}" data-model="CompanyUsers" data-page="1" data-size="9" data-select="id" data-callback="company_list_callback" data-wkey="company_id" data-wcomp="=" data-wval="{{$company_id}}">
      <div class="row common-class-head-text-c ajaxloadmorediv" id="append_company_list_callback">
      </div>
    </div>
</div>  
<!-- Modal -->
<div class="modal fade" id="myModalCompanyUser" role="dialog" data-url="{{ route('add_company_user') }}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <div class="row">
            <div class="col-sm-6">
                <h4 class="cust-card-title">
                  <i class="mdi  mdi-account cust-box-icon"></i>
                  Add User
                </h4>
            </div>
            <div class="col-sm-2">&nbsp;
            </div>
            <div class="col-sm-4">
            <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                          {{ csrf_field() }}
              <div class="form-group d-flex custom-search-view-place">
                  <input value="{{$search_input}}" type="text" name="search_input" id="search_input" class="form-control" placeholder="Search Here...." >
                  <button   class="btn btn-primary common_search_filter company_search_filter" ><i class="mdi mdi-magnify"></i></button>
              </div>
            </form>
            </div>
            </div>
              <div id="table_filter_view">
              </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


	
<script type="text/html" id="company_list_temp">
	<div class="col-md-4 grid-margin stretch-card"  id="tr<%=enc_id%>">
		<div class="card">
			<div class="card-body">
          <button type="button" class="cust-delete-btn delete btn btn-outline-danger btn-rounded btn-icon" data-model="CompanyUsers" data-id="<%=enc_id%>" data-url="{{ route('admin_delete') }}">
            <i class="mdi mdi-delete-forever " aria-hidden="true"></i>
          </button>
          <div class="d-flex align-items-center py-3 border-bottom">
            <div class="ml-0">
              <p class="mb-1">Name : <%=name%> <%=last_name%> </p>
            </div>
          </div>
          <div class="d-flex align-items-center py-3 border-bottom">
            <div class="ml-0">
              <p class="mb-1">Email : <%=email%> </p>
            </div>
          </div>
          <div class="d-flex align-items-center py-3 border-bottom">
            <div class="ml-0">
              <p class="mb-1">Phone : <%=contact%></p>
            </div>
          </div>
          <div class="d-flex align-items-center py-3">
            <div class="ml-0">
              <p class="mb-1">Card : <%=card_number%></p>
            </div>
          </div>
			</div>
		</div>
	</div>
</script>
<script>
$(document).ready(function(){
  ajaxLoadMore("true");
});
</script>
@endsection
