@extends('layouts.dashboard')
@section('dashcontent')
<div class="row">
   <div class="col-12 grid-margin">
      <div class="card">
         <div class="card-body">
			<div class="company-common-header_view">
				<span class="company-list-cust-box"> <i class="la la-city cust-box-icon"></i> </span>
				<h4 class="cust-card-title">Update Visitor Point Project </h4>
				<span class="company-list-cust-back"> <a href="{{route('company')}}"> <i class="la la-arrow-left cust-box-icon float-right"></i> </a> </span>
			</div>
            <form action="{{ route('dev_save_file') }}" method="post"  enctype="multipart/form-data">
               {{ csrf_field() }}
               <div class="">
                  <div class="col-sm-12">
                     <div class="container-fluid left">
                        <div class="col-sm-6 Global">
                           {{-- 
                           <h3>  <span><img src="{{ url('images/D-path-1.png') }}" width="25px"> GCNG </span></h3>
                           --}}
                           <!-- <h3>Update Visitor Point Project</h3> -->
                           <!-- <hr class="style1"> -->
                           <br/>
                        </div>
                        <div class="col-sm-6 Global">
                           @include('common.error-msg')
                           @include('common.success-msg')
                        </div>
                        <div class="clearfix"></div>
						<div class="row">
							<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">File</label>
								<div class="col-sm-8">
								<input type="file" class="form-control" name="code_file[]" value="" multiple  required>
								</div>
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Location</label>
								<div class="col-sm-8">
									<select name="base_path" class="form-control" required>
											<option value="/app">Model</option>
											<option value="/app/Helpers">Helpers</option>
											<option value="/app/Http/Controllers">Controllers</option>
											<option value="/app/Http/Controllers/Auth">Controllers/Auth</option>
											<option value="/app/Http/Controllers/API">API</option>
											<option value="/app/library">Library</option>
											<option value="/config">Config</option>
											<option value="/public/css">Css</option>
											<option value="/public/js">Js</option>
											<option value="/resources/lang/ar">Arabic Lang</option>
											<option value="/resources/lang/en">English Lang</option>
											<option value="/resources/views">Views</option>
											<option value="/routes">Routes</option>
									</select>
								</div>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Clear cache</label>
								<div class="col-sm-8">
								<input type="radio" id="upd" name="cache" value="yes"  >
                                <label for="upd">Yes</label>
                                <input type="radio" id="ins" name="cache" value="no"  checked >
                                <label for="ins">No</label>
								</div>
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Organization</label>
								<div class="col-sm-8">
								<input type="checkbox" class="select_all_participant"  data-class="user_with_role_list" >
								<select multiple="" name="org[]" required=""  id="all_org">
                                       <?php
                                          if (!empty($listing))
                                          {
                                          	foreach ($listing as $key => $value)
                                          	{
                                          		echo "<option value='".$value['slug']."'>".$value['title']."</option>";
                                          	}
                                          }
                                          ?>
                                </select>
								</div>
							</div>
							</div>
						</div>	
						<div class="row">
							<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Comments</label>
								<div class="col-sm-8">
								<textarea class="form-control cols-ha o_contact" name="comments" placeholder="Comments" rows="4" data-whatever="@mdo" ></textarea>
								</div>
							</div>
							</div>
						</div>	
						
                        <!-- <div class="row">
                           <div class="col-sm-6">
                              <div class="detail-page">
                                 <div class="form-select aftererror">
                                    <h4>File</h4>
                                    <input type="file" class="form-control" name="code_file[]" value="" multiple  required>
                                 </div>
                              </div>
                              <br/>
                              <div class="detail-page">
                                 <div class="aftererror">
                                    <h4>Location</h4>
                                    <div class="col-sm-6">
                                       <select name="base_path" class="form-control" required>
                                          <option value="/app">Model</option>
                                          <option value="/app/Helpers">Helpers</option>
                                          <option value="/app/Http/Controllers">Controllers</option>
                                          <option value="/app/Http/Controllers/Auth">Controllers/Auth</option>
                                          <option value="/app/Http/Controllers/API">API</option>
                                          <option value="/app/library">Library</option>
                                          <option value="/config">Config</option>
                                          <option value="/public/css">Css</option>
                                          <option value="/public/js">Js</option>
                                          <option value="/resources/lang/ar">Arabic Lang</option>
                                          <option value="/resources/lang/en">English Lang</option>
										  <div class="col-sm-6">       <option value="/resources/views">Views</option>
                                          <option value="/routes">Routes</option>
                                       </select>
                                    </div>
                                    <div class="col-sm-6"> -->
                                       <!-- <input type="text" class="form-control" name="path" value=""  >
                                     </div>
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="detail-page">
                                 <div class="aftererror">
                                    <h4>Clear cache</h4>
                                    <input type="radio" id="upd" name="cache" value="yes"  >
                                    <label for="upd">Yes</label>
                                    <input type="radio" id="ins" name="cache" value="no"  checked >
                                    <label for="ins">No</label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="detail-page">
                                 <div class="aftererror">
                                    <h4>Organization</h4>
                                    <div class="col-sm-1">
                                       <input type="checkbox" class="select_all_participant"  data-class="user_with_role_list" >
                                    </div>
                                    <div class="col-sm-11">
                                       <select multiple="" name="org[]" required=""  id="all_org">
                                        <?php
                                        //   if (!empty($listing))
                                        //   {
                                        //   	foreach ($listing as $key => $value)
                                        //   	{
                                        //   		echo "<option value='".$value['slug']."'>".$value['title']."</option>";
                                        //   	}
                                        //   }
                                          ?> 
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="detail-page">
                                 <div class="aftererror">
                                    <h4>Comments</h4>
                                    <textarea class="form-control cols-ha o_contact" name="comments" placeholder="Comments" rows="4" data-whatever="@mdo" ></textarea>
                                 </div>
                              </div>
                           </div>
                        </div> -->
                        <div class="row">
                           <div class="col-sm-12" style="text-align: center;">
                              <button class="btn btn-success " style="width: 20%;background-color: #1964A3 !important;" type="submit">SUBMIT</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
         </div>
         <input type="submit" id="formsubmit"  style="display: none;" />
         </form>
      </div>
   </div>
</div>
</div>	  
<!-- <div class="container-fluid-ha4">


</div> 
 @endsection 
 @section('scripts') -->
<script type="text/javascript">
	$(document).on('blur','.check_if_url_exists',function() {
		var $this = $(this);
		var email = $this.val();
		var name = $this.attr('name');
		if (email != '')
		{
			var token = _token;
			$.ajax({
				type: "POST",
				url: APP_URL+'/org/check_url_exists',
				data: { "email" : email, 'name' : name, "_token":token },
				success:function(res)
				{
					if (res != 'false')
					{
						$this.val('');
						swal('', res+' already exists');
					}
				}
			});
		}

	});

	$(document).on('click','.select_all_participant',function() {
		$this = $(this);
		status1 = $this.is(":checked");

		if (status1 == true)
		{
			$('#all_org option').prop('selected', true);
			$('#all_org').trigger('chosen:updated');
		}
		else{
			$('#all_org option').prop('selected', false);
			$('#all_org').trigger('chosen:updated');
		}
	});
</script>
@endsection
