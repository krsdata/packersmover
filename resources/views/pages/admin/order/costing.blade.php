@extends('layouts.dashboard')
@section('dashcontent')

<div class="row">
    <div class="container">
        <div class="col-sm-12" >
            <!-- All costing qtuotations -->
            <h4> Items to Pack And Move </h4>
                <?php 
                $all = json_decode($data[0]->item_name);
                foreach($all as $akey => $aval)
                {
                    if($akey == 'Bubble' || $akey == 'Corrugated' || $akey == 'Foam' || $akey == 'Wrapping')
                    {
                        @$extra_key = $akey;
                    }
                    if($aval != 0 && $akey !='contact' && $akey != 'date' && $akey != 'origin_floor')
                    {?>
                       <p><?php echo $akey?>  <span>[ <?php echo @$extra_key;?> ]</span> : <span><?php echo $aval;?></span></p>

                   <?php }
                }
                ?>
            <!-- End costing quotations -->
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
            <form  name="stock_frm" id="stock_frm" class="form-sample f1" method="POST" action = "{{ url('/admin/order/order_update') }}"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <input type="hidden" name="id" id="id" value="@if(isset($data)){{en_de_crypt($data[0]->id,'e')}}@endif">
                <div class="col-12 grid-margin" >
                    <h5 style="margin:0px auto;width:20%;">Hide Charges from invoice  </h5>
                    <h5>Charges : </h5>
                    <!-- first div -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Packing</label>
                                <div class="col-sm-6">
                                <input type="number"  id="packing" class="form-control"
                                data-msg-required="packing is required" value="" required/>
                                <label id="packing-error-server" class="server-error" for="packing"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Transport</label>
                                <div class="col-sm-6">
                                <input type="number"  id="transport" class="form-control"
                                data-msg-required="transport is required" value="" required/>
                                <label id="transport-error-server" class="server-error" for="transport"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Loading</label>
                                <div class="col-sm-6">
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
                            <label class="col-sm-4 col-form-label">Unloading</label>
                                <div class="col-sm-6">
                                <input type="number"  id="unloading" class="form-control"
                                data-msg-required="unloading is required" value="" required/>
                                <label id="unloading-error-server" class="server-error" for="unloading"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Unpacking</label>
                                <div class="col-sm-6">
                                <input type="number"  id="unpacking" class="form-control"
                                data-msg-required="unpacking is required" value="" required/>
                                <label id="unpacking-error-server" class="server-error" for="unpacking"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">AC</label>
                                <div class="col-sm-6">
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
                            <label class="col-sm-4 col-form-label">Local</label>
                                <div class="col-sm-6">
                                <input type="number"  id="local" class="form-control"
                                data-msg-required="local is required" value="" required/>
                                <label id="local-error-server" class="server-error" for="local"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Car Transport</label>
                                <div class="col-sm-6">
                                <input type="number"  id="car_transport" class="form-control"
                                data-msg-required="car_transport is required" value="" required/>
                                <label id="car_transport-error-server" class="server-error" for="car_transport"></label>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Insurance</label>
                                <div class="col-sm-6">
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
                            <label class="col-sm-4 col-form-label">GST</label>
                                <div class="col-sm-6">
                                <input type="number"  id="gst" class="form-control"
                                data-msg-required="gst is required" value="" required/>
                                <label id="gst-error-server" class="server-error" for="gst"></label>
                            </div>
                            </div>
                        </div>


                        <div class="col-md-4 offset-md-4">
                            <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Sub Total</label>
                                <div class="col-sm-6">
                                <input type="number"  id="sub_total" class="form-control"
                                data-msg-required="sub_total is required" value="" required/>
                                <label id="sub_total-error-server" class="server-error" for="sub_total"></label>
                            </div>
                            </div>
                        </div>
                        

                    </div>
                    <!-- end div -->

                    <!-- Transport div -->
                    <div class="row">

                        <div class="col-md-4">
                                <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Transport Gst</label>
                                    <div class="col-sm-6">
                                    <input type="number" id="transport_gst" class="form-control"
                                    data-msg-required="transport_gst is required" value="" required/>
                                    <label id="transport_gst-error-server" class="server-error" for="transport_gst"></label>
                                </div>
                                </div>
                            </div>
                        

                    </div>

                    <!-- End Div -->

                    <!-- discount div -->
                    <div class="row justify-content-end">
                        <div class="col-4">
                            <label class="col-sm-3 col-form-label">Discount</label>
                            <input type="number" id="discount" data-msg-required="discount is required" value="" required/>       
                            <label id="discount-error-server" class="server-error" for="discount"></label>
                        </div>
                        <div class="col-4">
                            <span id="gross_total">Gross Total: </span>
                        </div>
                    </div>

                    <!-- end div -->

                    <!-- advance payment div -->
                    <div class="row justify-content-end">
                        <div class="col-4">
                            <label class="col-sm-3 col-form-label">Advance Payment</label>
                            <input type="number" id="advance_payment" data-msg-required="advance_payment is required" value="" required/>       
                            <label id="advance_payment-error-server" class="server-error" for="advance_payment"></label>
                        </div>
                        <div class="col-4">
                            <span id="gross_total">Pending Amt: </span>
                        </div>
                    </div>

                    <!-- End div -->

                </div>
                <!-- End -->
                <button type="button" class="btn btn-primary mr-2 float-right " callback="after_create_action" onclick="ajaxCommonSumitForm(this)"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order">Save & Close</button>
            </form>
                
    </div>
    
</div>

@endsection

<style>
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
  padding: 0 20px;
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
</style>
