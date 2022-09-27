@extends('layouts.dashboard')
@section('dashcontent')
<style type="text/css">
  .content-wrapper{
    background: #fff!important;
  }
</style>
<div class="card">
  <div class="card-card">
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
      <th>Product Id</th>
        <th>Product Name</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Tax</th>
        <th>Discount</th>
        <th>Amount</th>    
      </tr>
    </thead>
    <tbody>
        @if(session('cart'))
        @foreach(session('cart') as $ckey => $cval)        
        <tr id="">
        <?php 
        //echo $cval['tax_name'];
        @$total += $cval['sale_price'] * $cval['quantity'];
        @$tax = $cval['sale_price'] * $cval['tax'] / 100;
        @$alltotal_tax += @$tax;
        @$discounts += $cval['discount'];
        //@$tax_name += $cval['tax_name'];
        ?>
         <td class="text-capitalize">{{$cval['id']}}</td>
            <td class="text-capitalize">{{$cval['name']}}</td>
            <td class="text-capitalize">{{$cval['quantity']}}</td>
            <td class="text-capitalize">{{$cval['sale_price']}}</td>
            <td class="text-capitalize">{{$cval['tax']}}%</td>
            <td class="text-capitalize">{{$cval['discount']}}</td>
            <td class="text-capitalize" ><?php echo $all = $cval['quantity'] * $cval['sale_price']; ?></td>
            <td class="text-capitalize">            
            <a href="{{ url('/admin/order/remove_product_Cart/' . en_de_crypt($cval['id'],'e') ) }}">
            <button class="btn badge-primary btn-xs" data-id="{{en_de_crypt($cval['id'],'e')}}"><i class="mdi mdi-delete"></i>
            </button>
            </a>
            </td>
        </tr>
        @endforeach        
        @endif     
        <tr>
        <td colspan="5" class="text-right">Price</td>
             <td colspan="2" id="total_amount1"><?php echo @$total;?></td>
         </tr> 
         <tr>
        <td colspan="5" class="text-right">Tax</td>
             <td colspan="2" id="tax"><?php echo @$alltotal_tax;?></td>
          
         </tr>  
         <tr>
        <td colspan="5" class="text-right">Discount</td>
             <td colspan="2" id="discount"><?php echo @$discounts;?></td>
         </tr>  
         <tr>
        <td colspan="5" class="text-right">Total Amount</td>
             <td colspan="2" id="total_amount"><?php echo @$total + @$alltotal_tax + @$discounts;?></td>
         </tr>         
    </tbody>
</table>
</div>
<!-- Customers dropdown -->
<div class="row m-0">
<div class="col-sm-3 col-form-label">
<select class="form-control dy_product" name="user_id" id="userids">
<option value="0">Select Customer</option>
  @if(isset($customer))
  @foreach($customer as $cust)
    <option value="{{$cust->id}}">{{$cust->name}}</option>
    @endforeach
  @endif 
</select>
</div>
<!-- end -->
 <div class="col-sm-9  mt-4">
   <button type="button" class="btn btn-primary mr-4 float-right proceed-toorder"  id="create_order" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> Proceed To Order </button>
</div>
</div>
</div>
</div>

<script type="text/javascript">
  $('#create_order').on('click', function(event) {
      var total_amount = $( "#total_amount").text();
      var user_id = $("#userids").val();
      var tax     = $("#tax").text();
      var discount = $("#discount").text();
     // var quantity = (".get_id").text();

      if(user_id == '0')
      {
        swal('Error!','Select Customer','error');
        return false;
      }
        if(total_amount){
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $.ajax({
                url: APP_URL + '/admin/order/place_order',
                method: 'POST',
                data: {"total_amount":total_amount,"user_id":user_id,"tax":tax,"discount":discount},
                success: function (data) {
                    if(data.success=="True"){
                      if(data.data){

                        // console.log(data.data.quantity);                        
                        location.reload();
                        window.location.href = APP_URL+ '/admin/order-list';
                        
                     }
                    }else{
                      
                        // swal(
                        //     'Error!',
                        //     data.msg,
                        //     'error'
                        // )
                      //location.reload();
                    }  
                }
            });
        }else{
          swal('Error!','Product Not Found','error');
          return false;
          //console.log('dd');
        }
  });

</script>

@endsection