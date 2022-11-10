<?php $__env->startSection('dashcontent'); ?>




<div class="row quotation_page">
  <div class="col-12 grid-margin">

    <div class="card">
      <div class="card-body">
      <h4 class="cust-card-title"><i class="mdi mdi-format-list-bulleted cust-box-icon"></i> Add New Quotations</h4>
          <form  name="stock_frm" id="stock_frm" class="form-sample f1" method="POST" action = "<?php echo e(url('/admin/order/store')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
          <?php echo e(csrf_field()); ?>

          <div class="col-sm-12">&nbsp;
          </div>
          
          <!-- Category div quotations -->

                  <div class="row">
                    <div class="col-md-12 div_append">
                        <div class="f1-steps">
                        </div>
                       
                        <fieldset>
                    		    <h4>New Enquiry : </h4>
                              <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Origing</label>
                                        <input type="text" name="origin" id="origin" placeholder="Enter Origin..." class="f1-first-name form-control" 
                                        data-msg-required="Origin is required" value="" required/>
                                        <label id="origin-error-server" class="server-error" for="origin"></label>
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Lift Availability</label>
                                          <div class="custom-switch">
                                            <input type="checkbox" name="origin_lift_availability" value="origin_lift_availability" class="custom-control-input custom_switch1" id="customSwitch4">
                                            <label class="custom-control-label" for="customSwitch4"></label>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Floor</label>
                                        <div class="input-floor w-auto justify-content-end align-items-center">
                                        <input type="button" value="-" class="button-minus border rounded-circle  icon-shape icon-sm mx-1 " data-field="quantity" >
                                        <input type="number" step="1"  value="0" name="origin_floor" id="origin_floor" class="quantity-field border-0 text-center w-25 increase_qty" >
                                        <input type="button" value="+" class="button-plus border rounded-circle icon-shape icon-sm " data-field="quantity" >
                                    
                                        </div>
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Destination</label>
                                        <input type="text" name="destination" id="destination" placeholder="Enter Destination..." class="f1-first-name form-control" 
                                        data-msg-required="Destination is required" value="" required/>
                                        <label id="destination-error-server" class="server-error" for="destination"></label>
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Lift Availability</label>
                                          <div class="custom-switch">
                                            <input type="checkbox" name="destination_lift_availability" value="destination_lift_availability" class="custom-control-input custom_switch2" id="customSwitch2">
                                            <label class="custom-control-label" for="customSwitch2"></label>
                                          </div>
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Floor</label>
                                        <div class="input-floor w-auto justify-content-end align-items-center">
                                        <input type="button" value="-" class="button-minus border rounded-circle  icon-shape icon-sm mx-1 " data-field="quantity" >
                                        <input type="number" step="1"  value="0" name="destination_floor" id="destination_floor" class="quantity-field border-0 text-center w-25 increase_qty" >
                                        <input type="button" value="+" class="button-plus border rounded-circle icon-shape icon-sm " data-field="quantity" >
                                    
                                        </div>
                                    </div>
                                  </div>
                              </div>

                              <!-- customer details div -->
                              <h4>Customer Details : </h4>
                              <div class="row">

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Name</label>
                                        <input type="text" name="name"  id="name" placeholder="Enter Name..." class="f1-first-name form-control">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Contact</label>
                                        <input type="text" name="contact" id="contact" placeholder="Enter Contact..." class="f1-first-name form-control" >
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Email</label>
                                        <input type="text" name="email" id="email" placeholder="Enter Email..." class="f1-first-name form-control" >
                                        <label id="error_email" class="error" style="color: red;"></label>
                                      </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="f1-first-name">Move Date</label>

                                        <input type="text" placeholder="Select Date..." class="form-control form_datetime_start form_datetime" id="date" value="" readonly="" name="date">
                                      </div>
                                  </div>

                              </div>

                              <!-- end customer details div -->

                                <div class="f1-buttons">
                                    <button type="button" class="btn btn-next" >Next</button>
                                </div>

                            </fieldset>

                            <!-- second page -->
                            <fieldset>
                                <h4>Categories:</h4>
                                <!-- Categories div -->
                                   
                                <!-- main category div -->
                                   <ul class="nav nav-tabs" role="tablist">
                                      <?php if(!$category->isEmpty()): ?>
                                      <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="nav-item <?php echo e($clist->id == 1 ? 'active' : ''); ?>">
                                          <a class="nav-link <?php echo e($clist->id == 1 ? 'active' : ''); ?>" data-toggle="tab" href="#tabs-<?php echo e($clist->id); ?>" role="tab"><?php echo e($clist->name); ?></a>
                                        </li>
                                        <input type="hidden" value="<?php echo e($clist->name); ?>" id="category_name">
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      <?php endif; ?>
                                    </ul>
                                    <!-- End main category div -->

                                    <!-- Sub category -->
                                    <div class="tab-content" style="width: 100%; float: left;margin-bottom:10px">
                                        <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <div class="tab-pane <?php echo e($clist->id == 1 ? 'active' : ''); ?> col-md-12" id="tabs-<?php echo e($clist->id); ?>" role="tabpanel">
                                        
                                                <?php $__currentLoopData = $clist->sub_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                  <div class="col-md-4" style="float: left;">
                                                    <div class="col-sm-12">
                                                      <div class="card overflow-hidden">
                                                        <div class="card-content">
                                                          <div class="">
                                                          <!-- card-body cleartfix -->
                                                          <!-- media align-items-stretch -->
                                                            <div class="" row_id="<?php echo e($ckey->id); ?>">
                                                              <div class="align-self-center">
                                                                <i class="icon-pencil primary font-large-2 mr-2"></i>
                                                              </div>
                                                              <div class="media-body">

                                                                <div class="col-sm-6">
                                                                  <h5 name="<?php echo e($ckey->name); ?>" class="namecat_<?php echo e($ckey->id); ?> packing_data" data-toggle="modal" data-modal="<?php echo e($ckey->id); ?>" value="<?php echo e($ckey->name); ?>"><?php echo e($ckey->name); ?></h5>
                                                                  <span class="pack_<?php echo e($ckey->id); ?>"></span>
                                                                </div>

                                                                <div class="col-sm-6" id="CheckBoxList" style="display:none;">
                                                                <input type="checkbox" class="custom_switch1 extra_class" name="Bubble" id="<?php echo e($ckey->id); ?>" data-id="<?php echo e($ckey->id); ?>" value="<?php echo e($ckey->name); ?>">  Bubble
                                                                <input type="checkbox" class="custom_switch1 extra_class" name="Corrugated" id="<?php echo e($ckey->id); ?>" data-id="<?php echo e($ckey->id); ?>" value="<?php echo e($ckey->name); ?>">Corrugated
                                                                <input type="checkbox" class="custom_switch1 extra_class" name="Foam" id="<?php echo e($ckey->id); ?>" data-id="<?php echo e($ckey->id); ?>" value="<?php echo e($ckey->name); ?>">Foam
                                                                <input type="checkbox" class="custom_switch1 extra_class" name="Wrapping" id="<?php echo e($ckey->id); ?>" data-id="<?php echo e($ckey->id); ?>" value="<?php echo e($ckey->name); ?>">Wrapping
                                                                </div>

                                                                
                                                            </div>
                                                              <input type="hidden" name="wrap<?php echo e($ckey->name); ?>" class="getname_<?php echo e($ckey->id); ?>" value="">
                                                              <!-- <div class="col-sm-6">
                                                              <button type="button" class="btn btn-success packing_data" data-toggle="modal" data-modal="<?php echo e($ckey->id); ?>">Packing</button>
                                                                
                                                              </div> -->

                                                                <div class="align-self-center col-sm-6">
                                                                    <div class="input-floor w-auto justify-content-end align-items-center">
                                                                        <input type="button" value="-" class="button-minus border rounded-circle  icon-shape icon-sm mx-1 " data-field="quantity" >
                                                                        <input type="number" step="1"  value="0" name="<?php echo e($ckey->name); ?>"  class="quantity-field border-0 text-center w-25 increase_qty qty" >
                                                                        <input type="button" value="+" class="button-plus border rounded-circle icon-shape icon-sm " data-field="quantity" >
                                                                    </div>
                                                                </div>

                                                                

                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      </div>
                                                    </div>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              </div>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    
                                  <!-- end categories div -->

                                <div class="f1-buttons" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-previous">Previous</button>
                                    <button type="button" class="btn btn-next btn-success" id="check_save">Next</button>
                                    <!-- <button type="button" class="btn  btn-primary mr-2 float-right submit_form" callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order">Submit</button> -->
                                </div>
                            </fieldset>
                            
                            <!-- third page -->
                            <fieldset>
                                <h4>Costing :</h4>
                               
                                <!-- costing div start -->
                                <div class="row">
                                  <div class="container">
                                    <div class="col-sm-12" id="my_html">
                                        <h4> Items to Pack And Move </h4>
                                    </div>

                                    <!-- remarks -->
                                          <div class="col-sm-12">
                                              <h5>Remark : </h5>
                                              
                                              <div class="remarks">
                                                  <p>Move Safely and Gracefully.</p>
                                              </div>

                                          </div>
                                    <!-- End -->

                                     <!-- Charges -->
                                          <form  name="stock_frm" id="stock_frm" class="form-sample f1" method="POST" action = "<?php echo e(url('/admin/order/order_update')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                                                  <?php echo e(csrf_field()); ?>

                                              <input type="hidden" name="id" id="id" value="<?php if(isset($data)): ?><?php echo e(en_de_crypt($data[0]->id,'e')); ?><?php endif; ?>">
                                              <div class="col-12 grid-margin costing_css" >
                                                  <h5 style="margin:0px auto;text-align: center;">Hide Charges from invoice  </h5>
                                                  <h5>Charges : </h5>
                                                  <!-- first div -->
                                                  <div class="row">
                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Packing</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="packing"  id="packing" class="form-control"
                                                              data-msg-required="packing is required" value=""/>
                                                             
                                                          </div>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Transport</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="transport" id="transport" class="form-control"
                                                              data-msg-required="transport is required" value="" />
                                                             
                                                          </div>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Loading</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="loading" id="loading" class="form-control"
                                                              data-msg-required="loading is required" value="" />
                                                              
                                                          </div>
                                                          </div>
                                                      </div>

                                                  </div>
                                                  <!-- end div -->

                                                  <!-- second div -->
                                                  <div class="row">
                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Unloading</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="unloading" id="unloading" class="form-control"
                                                              data-msg-required="unloading is required" value="" />
                                                              
                                                          </div>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Unpacking</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="unpacking" id="unpacking" class="form-control"
                                                              data-msg-required="unpacking is required" value="" />
                                                             
                                                          </div>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">AC</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="ac" id="ac" class="form-control"
                                                              data-msg-required="ac is required" value="" />
                                                             
                                                          </div>
                                                          </div>
                                                      </div>

                                                  </div>
                                                  <!-- end div -->
                                                  <!-- third div -->
                                                  <div class="row">
                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Local</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="local" id="local" class="form-control"
                                                              data-msg-required="local is required" value="" />
                                                              
                                                          </div>
                                                          </div>
                                                      </div>
                                                      <input type="hidden" name="car_transport" id="car_transport" class="form-control"
                                                              data-msg-required="car_transport is required" value="0" />
                                                      <!-- <div class="col-md-4" style="visibility: hidden">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Car Transport</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="car_transport" id="car_transport" class="form-control"
                                                              data-msg-required="car_transport is required" value="0" />
                                                              
                                                          </div>
                                                          </div>
                                                      </div> -->

                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Insurance</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="insurance" id="insurance" class="form-control"
                                                              data-msg-required="insurance is required" value="" />
                                                             
                                                          </div>
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">Sub Total</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="sub_total" id="sub_total" class="form-control"
                                                              data-msg-required="sub_total is required" value="" required/>
                                                              <label id="sub_total-error-server" class="server-error" for="sub_total"></label>
                                                          </div>
                                                          </div>
                                                      </div>

                                                  </div>
                                                  <!-- end div -->

                                                  <!-- fourth div -->
                                                  <div class="row">
                                                    
                                                  <input type="hidden" name="gst" id="gst" class="form-control"
                                                              data-msg-required="gst is required" value="0" required/>
                                                      <!-- <div class="col-md-4" style="visibility: hidden">
                                                          <div class="form-group row">
                                                          <label class="col-sm-4 col-form-label">GST</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="gst" id="gst" class="form-control"
                                                              data-msg-required="gst is required" value="0" required/>
                                                              <label id="gst-error-server" class="server-error" for="gst"></label>
                                                          </div>
                                                          </div>
                                                      </div>

                                                      <div class="col-md-4" style="visibility: hidden">
                                                              <div class="form-group row">
                                                              <label class="col-sm-4 col-form-label">Transport Gst</label>
                                                                  <div class="col-sm-6">
                                                                  <input type="number" name="transport_gst" id="transport_gst" class="form-control"
                                                                  data-msg-required="transport_gst is required" value="0"/>
                                                                 
                                                              </div>
                                                              </div>
                                                          </div> -->
        <input type="hidden" name="transport_gst" id="transport_gst" class="form-control" 
                                                                  data-msg-required="transport_gst is required" value="0"/>
                                                     
                                                      

                                                  </div>
                                                  <!-- end div -->

                                                  <!-- discount div -->
                                                  <div class="row">
                                                    <div class="col-md-4 offset-md-4">
                                                      <div class="form-group row">
                                                        
                                                            <label class="col-sm-4 col-form-label">Discount</label>
                                                            <div class="col-sm-6">
                                                              <input type="number" name="discount" id="discount" class="form-control" data-msg-required="discount is required" value=""/>
                                                            </div>
                                                      </div>
                                                      </div>

                                                        <div class="col-md-4">
                                                          <div class="form-group row">
                                                            
                                                                <label class="col-sm-4 col-form-label">Gross Total</label>
                                                                <div class="col-sm-6">
                                                                  <input type="number" name="gross_total" id="gross_total" class="form-control" value=""/>
                                                                </div>
                                                          </div>
                                                        </div>
                                                  </div>

                                                  <!-- end div -->

                                                  <!-- advance payment div -->
                                                  <div class="row">
                                                      <div class="col-md-4 offset-md-4">
                                                        <div class="form-group row">
                                                              <label class="col-sm-4 col-form-label">Advance Payment</label>
                                                              <div class="col-sm-6">
                                                              <input type="number" name="advance_payment" id="advance_payment" class="form-control" value=""/>
                                                              </div>
                                                        </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                          <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Pending Amount</label>
                                                                <div class="col-sm-6">
                                                                <input type="number" name="pending_amt" id="pending_amt" class="form-control"  value=""/>
                                                                </div>
                                                          </div>
                                                        </div>

                                                      
                                                  </div>

                                                  <!-- End div -->

                                              </div>
                                              <!-- End -->
                                              <!-- gst amount and transport gst amount in hidden field -->
                                              <input type="hidden" name="gst_amt" value="" id="gst_amt">
                                              <input type="hidden" name="transport_gst_amt" value="" id="transport_gst_amt">
                                              <input type="hidden" name="total" value="" id="total">
                                              <div class="f1-buttons">
                                                <button type="button" class="btn btn-previous destory_session">Previous</button>
                                                <button type="submit" class="btn btn-submit" >Save & Close</button>
                                            </div>
                                              <!-- <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order">Save & Close</button> -->
                                          </form>

                                  </div>
                                  </div>
                                <!-- end costing div -->

                               
                            </fieldset>
                       
                    </div>
                  </div>

          <!-- End div category -->
             
        </form>
      
    </div>
  </div>
</div>
</div>

<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title title_name"></h5>
        
      </div>
    <div class="modal-body">

        <div class="col-md-12">

          <div class="form-check">
          <input type="checkbox" class="form-check-input custom_switch extra_class " name="sport" value="Bubble"> 
          <label class="form-check-label" for="flexCheckDefault">
            Bubble  </label> 
          </div>

          <div class="form-check">
          <input type="checkbox" class="form-check-input custom_switch extra_class " name="sport" value="Corrugated">
          <label class="form-check-label" for="flexCheckDefault"> Corrugated </label> 
          </div>

          <div class="form-check">
          <input type="checkbox" class="form-check-input custom_switch extra_class " name="sport" value="Foam">
          <label class="form-check-label" for="flexCheckDefault">Foam </label> 
          </div>

          <div class="form-check">
          <input type="checkbox" class="form-check-input custom_switch extra_class " name="sport" value="Wrapping">
          <label class="form-check-label" for="flexCheckDefault">Wrapping </label> 
          </div>
        
        </div>

        <div class="col-md-12">
        <button type="button" class="btn btn-success modalsave">Save</button>
        </div>
       

    </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(".packing_data").click(function(){
  var id = $(this).attr('data-modal');
  
  if(id)
  {
    $('.bd-example-modal-sm').modal('toggle');
    //$(".bd-example-modal-sm").attr('class','modal fade bd-example-modal-sm_'+id);
    
  }

  //$(".extra_class").attr('name','sport'+id);
  $(".custom_switch").attr('data-mid',id);
  $(".modalsave").attr("data-id",id);

})

$(".modalsave").click(function(){
  var wraping = [];
  var myid;
 
  // $("input:checkbox").change(function() {
  //                   var ischecked= $(this).is(':checked');
  //                   if(!ischecked)
  //                     alert('uncheckd ' + $(this).val());
  //               }); 

    $.each($("input[name='sport']:checked"), function(){
                  myid = $(this).attr("data-mid");
                  var dataid = $(".packing_data").attr('data-modal');
                  wraping.push($(this).val());
                  var idval = $(this).attr("data-mid");
                  
            });
            
            $(".getname_"+myid).val(wraping.join(', '));
            $(".pack_"+myid).html(' - '+wraping.join(', '));
            $('.bd-example-modal-sm').modal('hide');
            $("input[name='sport']:checked").prop('checked', false); 

            
});

$(document).on('click','#check_save',function(e) {
e.preventDefault();

  var alldata = $("#stock_frm").serialize();
  

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/order/get_serializedata',
                method: 'POST',
                data: alldata,
                success: function (data) {
                    if(data){
                      
                      var d = JSON.parse(JSON.stringify(data));
                      $('#my_html').html(d.data);
                      
                    }else{
                      
                    }  
                }
            });
});


$(".bubble_modal").click(function(){
  
  var id = $(this).attr('data-id');
  $('.custom_switch').attr('data-id',id);
  $('.custom_switch1').attr('data-id',id);
 
});

$(".custom_switch").on('change',function(){
 var id = $(this).attr('data-mid');

    if(id)
    {
      var checkIn = $('input[data-mid="'+id+'"]').not(this).prop('checked', false);
    }

})

// $(".custom_switch1").on('change',function(){
//  var id = $(this).attr('data-id');

//     if(id)
//     {
//       var checkIn = $('input[name="bu"]').not(this).prop('checked', false);
//     }

// })


$(".save_id").click(function(){
  var n = $( "input:checked" ).val();
  var catid = $(this).attr('id');
  $(".increase_qty").attr('cl',n);
})


function scroll_to_class(element_class, removed_height) {
  var scroll_to = $(element_class).offset().top - removed_height;
  if ($(window).scrollTop() != scroll_to) {
    $("html, body").stop().animate({ scrollTop: scroll_to }, 0);
  }
}

function bar_progress(progress_line_object, direction) {
  var number_of_steps = progress_line_object.data("number-of-steps");
  var now_value = progress_line_object.data("now-value");
  var new_value = 0;
  if (direction == "right") {
    new_value = now_value + 100 / number_of_steps;
  } else if (direction == "left") {
    new_value = now_value - 100 / number_of_steps;
  }
  progress_line_object
    .attr("style", "width: " + new_value + "%;")
    .data("now-value", new_value);
}

jQuery(document).ready(function () {





  /*
        Fullscreen background
    */
  $.backstretch("assets/img/backgrounds/1.jpg");

  $("#top-navbar-1").on("shown.bs.collapse", function () {
    $.backstretch("resize");
  });
  $("#top-navbar-1").on("hidden.bs.collapse", function () {
    $.backstretch("resize");
  });

  /*
        Form
    */
  $(".f1 fieldset:first").fadeIn("slow");

  $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on(
    "focus",
    function () {
      $(this).removeClass("input-error");
    }
  );

  // next step
  $(".f1 .btn-next").on("click", function () {
    var parent_fieldset = $(this).parents("fieldset");
    var email = $("#email").val();
    if(email)
    {
      var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if (!filter.test(email)) {
        //alert('Please provide a valid email address');
        $("#error_email").text(email+" is not a valid email");
        email.focus;
        return false;
        //return false;
      } else {
          $("#error_email").text("");
          
      }
    }
   
    var next_step = true;
    // navigation steps / progress steps
    var current_active_step = $(this).parents(".f1").find(".f1-step.active");
    var progress_line = $(this).parents(".f1").find(".f1-progress-line");

    // fields validation
    parent_fieldset
      .find('input[type="text"],input[type="date"]')
      .each(function () {
        if ($(this).val() == "") {
          $(this).addClass("input-error");
          next_step = false;
          
        } else {
          $(this).removeClass("input-error");
        }
      });
    // fields validation

    if (next_step) {
      parent_fieldset.fadeOut(400, function () {
        // change icons
        current_active_step
          .removeClass("active")
          .addClass("activated")
          .next()
          .addClass("active");
        // progress bar
        bar_progress(progress_line, "right");
        // show next step
        $(this).next().fadeIn();
        // scroll window to beginning of the form
        scroll_to_class($(".f1"), 20);
      });
    }
  });

  // previous step
  $(".f1 .btn-previous").on("click", function () {
    // navigation steps / progress steps
    var current_active_step = $(this).parents(".f1").find(".f1-step.active");
    var progress_line = $(this).parents(".f1").find(".f1-progress-line");

    $(this)
      .parents("fieldset")
      .fadeOut(400, function () {
        // change icons
        current_active_step
          .removeClass("active")
          .prev()
          .removeClass("activated")
          .addClass("active");
        // progress bar
        bar_progress(progress_line, "left");
        // show previous step
        $(this).prev().fadeIn();
        // scroll window to beginning of the form
        scroll_to_class($(".f1"), 20);
      });
  });

 
});


function incrementValue(e) {
        e.preventDefault();
        
        var fieldName = document.getElementsByClassName('increase_qty');
        var parent = $(e.target).closest('div');
        var currentVal = parseInt(parent.find(fieldName).val(), 10);
       
        if (!isNaN(currentVal)) {
            parent.find(fieldName).val(currentVal + 1);
            
        } else {
            parent.find(fieldName).val(0);
        }
    }

    function decrementValue(e) {
        e.preventDefault();
        var fieldName = document.getElementsByClassName('increase_qty');
        var parent = $(e.target).closest('div');
        var currentVal = parseInt(parent.find(fieldName).val(), 10);

        if (!isNaN(currentVal) && currentVal > 0) {
            parent.find(fieldName).val(currentVal - 1);
        } else {
            parent.find(fieldName).val(0);
        }
    }

    $('.input-floor').on('click', '.button-plus', function(e) {
      incrementValue(e);
        
    });

    $('.input-floor').on('click', '.button-minus', function(e) {
        decrementValue(e);
    });

  
</script>


<style>
  .packing_data
  {
    display:inline-block;
    cursor:pointer;
  }
  .costing_css, .form-group {
    margin-bottom: 0rem;

  }
 .remarks 
{
    border: 1px solid #000;
    padding: 10px 10px 10px 10px;
    margin-bottom: 20px;
}
 .content-wrapper {
    background: #fff !important;

}

input[type="number"],
input[type="password"],
textarea,
textarea.form-control {
  height: 44px;
  margin: 0;
  /* padding: 0 20px; */
  vertical-align: middle;
  background: #fff;
  border: 1px solid #a6a2a2;
  font-family: "Roboto", sans-serif;
  font-size: 16px;
  font-weight: 300;
  line-height: 44px;
  color: #888;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  -moz-box-shadow: none;
  -webkit-box-shadow: none;
  box-shadow: none;
  -o-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -webkit-transition: all 0.3s;
  -ms-transition: all 0.3s;
  transition: all 0.3s;
} 
/* start css for switches pop up */
*,
::after,
::before {
  box-sizing: border-box;
}

.first-label {
  margin-right: 49px;
}

.custom-control {
  position: relative;
  display: block;
  min-height: 1.5rem;
  padding-left: 1.5rem;
}

.custom-switch {
  padding-left: 76px;
}
.custom-switch .custom-control-label::before {
  left: -49px;
  width: 42px;
  height: 21px;
  pointer-events: all;
  border-color: #dddddd;
  border-radius: 21px;
}
.custom-switch .custom-control-label::after {
  top: calc(0.25rem + 2px);
  left: -47px;
  width: 1rem;
  height: 1rem;
  background-color: rgba(255, 255, 255, 0.7);
  border-radius: 0.5rem;
  transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-transform 0.15s ease-in-out;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-transform 0.15s ease-in-out;
}

input[type=checkbox] {
  box-sizing: border-box;
  padding: 0;
}

label {
  display: inline-block;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}
.custom-control-label::before {
  position: absolute;
  top: 0.25rem;
  left: -1.5rem;
  display: block;
  width: 1rem;
  height: 1rem;
  pointer-events: none;
  content: "";
  background-color: #dddddd;
  box-shadow: 0 0 1px 0 #dddddd inset, 0 0 1px 0 #dddddd;
  border: #adb5bd solid 1px;
  transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.custom-control-label::after {
  position: absolute;
  top: 0.25rem;
  left: -1rem;
  display: block;
  width: 1rem;
  height: 1rem;
  content: "";
  background: rgba(255, 255, 255, 0.7);
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1rem;
  height: 1.25rem;
  opacity: 0;
}
.custom-control-input:checked ~ .custom-control-label::before {
  color: #fff;
  border-color: #00adef;
  background-color: #00adef;
}
.custom-control-input:checked ~ .custom-control-label::after {
  background-color: #fff;
  -webkit-transform: translateX(1.35rem);
  transform: translateX(1.35rem);
}
/* End switches css for pop up */

  /***** Top content for next previous design *****/

.top-content {
  padding: 40px 0 170px 0;
}

.top-content .text {
  color: #fff;
}
.top-content .text h1 {
  color: #fff;
}
.top-content .description {
  margin: 20px 0 10px 0;
}
.top-content .description p {
  opacity: 0.8;
}
.top-content .description a {
  color: #fff;
}
.top-content .description a:hover,
.top-content .description a:focus {
  border-bottom: 1px dotted #fff;
}

.form-box {
  padding-top: 40px;
}

.f1 {
  padding: 25px;
  background: #fff;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
}
.f1 h3 {
  margin-top: 0;
  margin-bottom: 5px;
  text-transform: uppercase;
}

.f1-steps {
  overflow: hidden;
  position: relative;
  margin-top: 20px;
}

.f1-progress {
  position: absolute;
  top: 24px;
  left: 0;
  width: 100%;
  height: 1px;
  background: #ddd;
}
.f1-progress-line {
  position: absolute;
  top: 0;
  left: 0;
  height: 1px;
  background: #f35b3f;
}

.f1-step {
  position: relative;
  float: left;
  width: 33.333333%;
  padding: 0 5px;
}

.f1-step-icon {
  display: inline-block;
  width: 40px;
  height: 40px;
  margin-top: 4px;
  background: #ddd;
  font-size: 16px;
  color: #fff;
  line-height: 40px;
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  border-radius: 50%;
}
.f1-step.activated .f1-step-icon {
  background: #fff;
  border: 1px solid #f35b3f;
  color: #f35b3f;
  line-height: 38px;
}
.f1-step.active .f1-step-icon {
  width: 48px;
  height: 48px;
  margin-top: 0;
  background: #f35b3f;
  font-size: 22px;
  line-height: 48px;
}

.f1-step p {
  color: #ccc;
}
.f1-step.activated p {
  color: #f35b3f;
}
.f1-step.active p {
  color: #f35b3f;
}

.f1 fieldset {
  display: none;
  text-align: left;
}

.f1-buttons {
  text-align: right;
}

.f1 .input-error {
  border-color: #f35b3f;
}

input[type="text"],
input[type="password"],
textarea,
textarea.form-control {
  height: 44px;
  margin: 0;
  padding: 0 20px;
  vertical-align: middle;
  background: #fff;
  border: 1px solid #ddd;
  font-family: "Roboto", sans-serif;
  font-size: 16px;
  font-weight: 300;
  line-height: 44px;
  color: #888;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  -moz-box-shadow: none;
  -webkit-box-shadow: none;
  box-shadow: none;
  -o-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -webkit-transition: all 0.3s;
  -ms-transition: all 0.3s;
  transition: all 0.3s;
}

textarea,
textarea.form-control {
  height: 90px;
  padding-top: 8px;
  padding-bottom: 8px;
  line-height: 30px;
}

input[type="text"]:focus,
input[type="password"]:focus,
textarea:focus,
textarea.form-control:focus {
  outline: 0;
  background: #fff;
  border: 1px solid #ccc;
  -moz-box-shadow: none;
  -webkit-box-shadow: none;
  box-shadow: none;
}

input[type="text"]:-moz-placeholder,
input[type="password"]:-moz-placeholder,
textarea:-moz-placeholder,
textarea.form-control:-moz-placeholder {
  color: #888;
}

input[type="text"]:-ms-input-placeholder,
input[type="password"]:-ms-input-placeholder,
textarea:-ms-input-placeholder,
textarea.form-control:-ms-input-placeholder {
  color: #888;
}

input[type="text"]::-webkit-input-placeholder,
input[type="password"]::-webkit-input-placeholder,
textarea::-webkit-input-placeholder,
textarea.form-control::-webkit-input-placeholder {
  color: #888;
}

label {
  font-weight: 300;
}

button.btn {
  min-width: 105px;
  height: 40px;
  margin: 0;
  padding: 0 20px;
  vertical-align: middle;
  border: 0;
  font-family: "Roboto", sans-serif;
  font-size: 16px;
  font-weight: 300;
  line-height: 40px;
  color: #fff;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  text-shadow: none;
  -moz-box-shadow: none;
  -webkit-box-shadow: none;
  box-shadow: none;
  -o-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -webkit-transition: all 0.3s;
  -ms-transition: all 0.3s;
  transition: all 0.3s;
}

button.btn:hover {
  opacity: 0.6;
  color: #fff;
}
button.btn:active {
  outline: 0;
  opacity: 0.6;
  color: #fff;
  -moz-box-shadow: none;
  -webkit-box-shadow: none;
  box-shadow: none;
}
button.btn:focus,
button.btn:active:focus,
button.btn.active:focus {
  outline: 0;
  opacity: 0.6;
  color: #fff;
}

button.btn.btn-next,
button.btn.btn-next:focus,
button.btn.btn-next:active:focus,
button.btn.btn-next.active:focus {
  background: #f35b3f;
}

button.btn.btn-submit,
button.btn.btn-submit:focus,
button.btn.btn-submit:active:focus,
button.btn.btn-submit.active:focus {
  background: #f35b3f;
}

button.btn.btn-previous,
button.btn.btn-previous:focus,
button.btn.btn-previous:active:focus,
button.btn.btn-previous.active:focus {
  background: #bbb;
}

/***** Media queries *****/

@media (min-width: 992px) and (max-width: 1199px) {
}

@media (min-width: 768px) and (max-width: 991px) {
}

@media (max-width: 767px) {
  .navbar {
    padding-top: 0;
  }
  .navbar.navbar-no-bg {
    background: #333;
    background: rgba(51, 51, 51, 0.9);
  }
  .navbar-brand {
    height: 60px;
    margin-left: 15px;
  }
  .navbar-collapse {
    border: 0;
  }
  .navbar-toggle {
    margin-top: 12px;
  }

  .top-content {
    padding: 40px 0 110px 0;
  }
}

@media (max-width: 415px) {
  h1,
  h2 {
    font-size: 32px;
  }

  .f1 {
    padding-bottom: 20px;
  }
  .f1-buttons button {
    margin-bottom: 5px;
  }
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.tabs-wrap {
	margin-top: 40px;
}
.tab-content .tab-pane {
	padding: 20px 0;
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/app/resources/views/pages/admin/order/create.blade.php ENDPATH**/ ?>