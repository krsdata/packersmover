@extends('layouts.dashboard')
@section('dashcontent')

          <div class="row">
              <div class="col-lg-12">
                  <div class="card px-2" id="invoice-POS">
                      <div class="card-body">
                          <div class="container-fluid">
                            <h3 class="text-right my-3">Invoice&nbsp;&nbsp;{{@$order[0]->id}}</h3>
                            <hr>
                          </div>
                          <div class="container-fluid d-flex justify-content-between">
                            <div class="col-lg-3 pl-0">
                              <p class="mt-3 mb-2"><b>{{@$order[0]->user_name}}</b></p>
                              <p>{{@$order[0]->user_email}}</p>
                              <p>{{@$order[0]->user_mobile}}</p>
                            </div>

                            <!-- <div class="col-lg-3 pr-0">
                              <p class="mt-5 mb-2 text-right"><b>Invoice to</b></p>
                              <p class="text-right">Gaala &amp; Sons,<br> C-201, Beykoz-34800,<br> Canada, K1A 0G9.</p>
                            </div> -->

                          </div>
                          <div class="container-fluid d-flex justify-content-between">
                            <div class="col-lg-3 pl-0">
                              <p class="mb-0 mt-5">Order Date : {{@$order[0]->created_at}}</p>
                              <!-- <p>Due Date : 25th Jan 2017</p> -->
                            </div>
                          </div>
                          <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                            <div class="table-responsive w-100">
                                <table class="table">
                                  <thead>
                                    <tr class="bg-dark text-white">
                                        <th>#</th>
                                        <th>Description</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Unit cost</th>
                                        <th class="text-right">Total</th>
                                      </tr>
                                  </thead>
                                 
                                  <tbody>
                                  @if(!$order_items->isEmpty())
                                  <?php $i=1;?>
                                  @foreach($order_items as $list)
                                  <?php @$subtotal += $list['qty'] * $list['price'];
                                      @$total_tax += $list['tax'];
                                      //@$exclive_price += ($subtotal - $total_tax);
                                      @$total_disc += $list['discount'];
                                      @$tax_type .= $list['tax_type'];
                                      if($tax_type == '1')
                                      {
                                        @$tax_typeA = 'Tax A 18%' .'<br/>';
                                      }
                                      else if($tax_type == '2')
                                      {
                                        @$tax_typeA .= 'Tax B 0%' .'<br/>';
                                      }
                                      ?>
                                    <tr class="text-right">
                                      <td class="text-left"><?php echo $i++;?></td>
                                      <td class="text-left">{{$list->product_name}}</td>
                                      <td>{{$list->qty}}</td>
                                      <td>{{$list->price}}</td>
                                      <td>{{$list->sub_total}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                  </tbody>
                                  
                                </table>
                              </div>
                          </div>
                          <div class="container-fluid mt-5 w-100">
                            <p class="text-right mb-2">Total Exclusive Of Tax: TZS <?php $data = $subtotal - $total_tax;echo round($data,2);?></p>
                            <p class="text-right mb-2">Discount: TZS  <?php echo round(@$total_disc,2);?></p>
                            <p class="text-right mb-2"> <?php echo @$tax_typeA;?><?php echo round(@$total_tax,2);?></p>
                            <p class="text-right mb-2">Total Tax: TZS  <?php echo round(@$total_tax,2);?></p>
                            <p class="text-right mb-2">Total Inclusive Of Tax: TZS <?php echo @$subtotal;?></p>                            
                            <!-- <h4 class="text-right mb-5">Total : <?php echo @$subtotal;?></h4> -->
                            <hr>
                          </div>
                          <div class="container-fluid w-100">
                            <a href="#" class="printPage btn btn-primary float-right mt-4 ml-2"><i class="mdi mdi-printer mr-1"></i>Print</a>
                            <!-- <a href="#" class="btn btn-success float-right mt-4"><i class="mdi mdi-telegram mr-1"></i>Send Invoice</a> -->
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          
<script>
$('.printPage').click(function(){
 //$("#invoice-POS").print();
   window.print();
  return false;
});



</script>

@endsection