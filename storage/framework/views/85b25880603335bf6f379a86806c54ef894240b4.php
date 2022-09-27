
<?php $__env->startSection('dashcontent'); ?>

<div class="row">
    <div class="container">
        <div class="col-sm-12" style="margin:0px auto;width:55%;">
            <!-- All costing qtuotations -->
            <h4> Items to Pack And Move </h4>
                <?php 
                $all = json_decode($data[0]->item_name);
                foreach($all as $akey => $aval)
                {
                    if($aval != 0 && $akey !='contact')
                    {?>
                       <p><?php echo $akey?> : <span><?php echo $aval;?></span></p>

                   <?php }
                }
                ?>
            <!-- End costing quotations -->
        </div>
        <!-- remarks -->
                <div class="col-sm-12" style="margin:0px auto;width:55%;">
                    <p>Remark : </p>
                    
                    <div class="remarks">
                        <p>Move Safely and Gracefully.</p>
                    </div>

                </div>
        <!-- End -->

                <!-- Charges -->
            <form  name="stock_frm" id="stock_frm" class="form-sample f1" method="POST" action = "<?php echo e(url('/admin/order/order_update')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="id" value="<?php if(isset($data)): ?><?php echo e(en_de_crypt($data[0]->id,'e')); ?><?php endif; ?>">
                <div class="col-12 grid-margin" >
                    <p>Hide Charges from invoice : </p>
                    
                    <!-- first div -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Packing</label>
                                <div class="col-sm-9">
                                <input type="number"  id="packing" class="form-control"
                                data-msg-required="packing is required" value="" required/>
                                <label id="packing-error-server" class="server-error" for="packing"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Transport</label>
                                <div class="col-sm-9">
                                <input type="number"  id="transport" class="form-control"
                                data-msg-required="transport is required" value="" required/>
                                <label id="transport-error-server" class="server-error" for="transport"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Loading</label>
                                <div class="col-sm-9">
                                <input type="number"  id="loading" class="form-control"
                                data-msg-required="loading is required" value="" required/>
                                <label id="loading-error-server" class="server-error" for="loading"></label>
                            </div>
                            </div>
                        </div>

                    </div>
                    <!-- end div -->

                    <!-- second div -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Unloading</label>
                                <div class="col-sm-9">
                                <input type="number"  id="unloading" class="form-control"
                                data-msg-required="unloading is required" value="" required/>
                                <label id="unloading-error-server" class="server-error" for="unloading"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Unpacking</label>
                                <div class="col-sm-9">
                                <input type="number"  id="unpacking" class="form-control"
                                data-msg-required="unpacking is required" value="" required/>
                                <label id="unpacking-error-server" class="server-error" for="unpacking"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">AC</label>
                                <div class="col-sm-9">
                                <input type="number"  id="ac" class="form-control"
                                data-msg-required="ac is required" value="" required/>
                                <label id="ac-error-server" class="server-error" for="ac"></label>
                            </div>
                            </div>
                        </div>

                    </div>
                     <!-- end div -->
                     <!-- third div -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Local</label>
                                <div class="col-sm-9">
                                <input type="number"  id="local" class="form-control"
                                data-msg-required="local is required" value="" required/>
                                <label id="local-error-server" class="server-error" for="local"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Car Transport</label>
                                <div class="col-sm-9">
                                <input type="number"  id="car_transport" class="form-control"
                                data-msg-required="car_transport is required" value="" required/>
                                <label id="car_transport-error-server" class="server-error" for="car_transport"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Insurance</label>
                                <div class="col-sm-9">
                                <input type="number"  id="insurance" class="form-control"
                                data-msg-required="insurance is required" value="" required/>
                                <label id="insurance-error-server" class="server-error" for="insurance"></label>
                            </div>
                            </div>
                        </div>

                    </div>
                    <!-- end div -->

                    <!-- fourth div -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">GST</label>
                                <div class="col-sm-9">
                                <input type="number"  id="gst" class="form-control"
                                data-msg-required="gst is required" value="" required/>
                                <label id="gst-error-server" class="server-error" for="gst"></label>
                            </div>
                            </div>
                        </div>

                        <!-- <div class="col-md-4" >
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">GST</label>
                                <div class="col-sm-9">
                                <input type="number"  id="gst" class="form-control"
                                data-msg-required="gst is required" value="" required/>
                                <label id="gst-error-server" class="server-error" for="gst"></label>
                            </div>
                            </div>
                        </div> -->

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Sub Total</label>
                                <div class="col-sm-9">
                                <input type="number"  id="sub_total" class="form-control"
                                data-msg-required="sub_total is required" value="" required/>
                                <label id="sub_total-error-server" class="server-error" for="sub_total"></label>
                            </div>
                            </div>
                        </div>

                    </div>
                    <!-- end div -->

                    <!-- discount div -->
                    <div class="col-sm-8" style="float:right;">
                        <div class="col-md-5">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Discount</label>
                                <div class="col-sm-9">
                                <input type="number"  id="discount" class="form-control"
                                data-msg-required="discount is required" value="" required/>
                                <label id="discount-error-server" class="server-error" for="discount"></label>
                            </div>
                            </div>
                        </div>
                        <span style="float: right;position: absolute;top: 22%;right: 25%;" id="gross_total">Gross Total: </span>
                    </div>
                    <!-- end div -->

                    <!-- advance payment div -->
                    <div class="col-sm-8" style="float:right;">
                        <div class="col-md-5">
                            <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Advance Payment</label>
                                <div class="col-sm-9">
                                <input type="number"  id="advance_payment" class="form-control"
                                data-msg-required="advance_payment is required" value="" required/>
                                <label id="advance_payment-error-server" class="server-error" for="advance_payment"></label>
                            </div>
                            </div>
                        </div>
                        <span style="float: right;position: absolute;top: 22%;right: 25%;">Pending Amount : </span>
                    </div>
                    <!-- End div -->

                </div>
                <!-- End -->
                <!-- <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order">Save & Close</button> -->
            </form>
                
    </div>
    
</div>

<?php $__env->stopSection(); ?>

<style>
    .remarks {
        border: 1px solid #000;
        padding: 10px 10px 10px 10px;
        width: 40%;
    }
    
</style>
<script>
    window.onload = function() {
        var pack = document.getElementById('packing');
        var trans = document.getElementById('transport');
        var packing;
       pack.onkeyup = function()
       {
           
           packing = document.getElementById('sub_total').setAttribute('value',$(this).val());
           
       }

    //    trans.onkeyup = function()
    //    {
    //        var pck = document.getElementById('packing').value();
    //        console.log(pck);
    //        document.getElementById('sub_total').setAttribute('value',$(this).val() + packing);
    //    }
    };
    
</script>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/saipacker/public_html/resources/views/pages/admin/order/costing.blade.php ENDPATH**/ ?>