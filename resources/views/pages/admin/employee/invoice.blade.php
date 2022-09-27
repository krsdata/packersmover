<!DOCTYPE html>
<html lang="en">
<head>
  <title>SaiPackersAndMovers</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://localhost/saipackers/vendors/iconfonts/mdi/font/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
        <!-- style="margin: 0px auto;width: 55%;background-color: #fff;" -->
    <a href="{{ url ('/admin/order-list') }}">Home</a>
    <div class="container bootstrap snippets bootdeys" style="background-color: #fff;width:60%;" >
        <div class="row">
                <div class="col-sm-12">
                    
                    <div class="panel panel-default invoice" id="invoice">
                    <div class="panel-body">
                        
                        <div class="row" style="padding-top: 50px;">

                            <div class="col-sm-12">
                                <img src="{{ asset('images/pdf_logo.jpg') }}" alt="saipackersandmovers" class="saipackers_logo">
                            </div>

                        </div>
                       

                        <div class="container" style="padding-top: 15px;padding-bottom: 20px;">
                            <div class="row" style="border: 1px solid #000;height: 157px;max-height: 160px;">
                                    <!-- User details -->
                                    <div class="user_info">
                                        
                                            <span class="user_name">Customer Name: {{$order->name}}</span>
                                       
                                        
                                            <span class="user_contact">Mobile Number: {{$order->contact}}</span>
                                     
                                       
                                            <span class="user_invoiceid">Invoice #:{{$order->id}}</span>
                                        
                                        
                                            <span class="user_date">Date: {{$order->date}}</span>
                                        
                                    </div>
                                    <!-- End -->
                                
                                <div class="col-md-6 from">
                                    <p style="padding-top:10px;"><strong style="color:#1a1b1e;">Move Date:</strong> {{$order->date}} <strong style="color:#1a1b1e;">Time: </strong>09:00 AM</p>
                                    
                                    <div class="origin_add">
                                       <div style="background-color: #edb581;"> <span style="margin-left: 11px;font-weight: bold;color:#47280c;">Origin</span></div>
                                        <p style="position: absolute;bottom: 1%;left: 28px;color:#252628;">{{$order->origin}}</p>
                                    </div>
                                </div>

                                <div class="col-md-6 to">
                                    <p class="customer_email"><strong style="color:#1a1b1e;">Customer Email: </strong>{{$order->email}}</p>

                                    <div class="destination_add">
                                    <div style="background-color: #edb581;"><span style="margin-left: 11px;font-weight: bold;color:#47280c;">Destination</span></div>
                                        <p style="position: absolute;bottom: 1%;left: 28px;color:#252628;">{{$order->destination}}</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="container" >
                            <div class="row" style="margin-left: 1%;">
                                <p style="color:#2c2c2c;">Dear Sir/Madam,</p>
                                <p style="color:##282828;">We thank you for giving us the opportunity  to quote for relocation amd 
                                    shifting of your valuable goods. We are pleased to quote you our best rate offer
                                    for the same as under:
                                </p>
                            </div>
                        </div>
                        <!-- Packagin item in table -->
                        <div class="container" style="margin-bottom: 20px;">
                        <div class="row table-row">
                            
                                <table class="table table-striped">
                                <thead>
                                    <tr style="background-color: #e77815;line-height: 6px;color: #fff;">
                                    <?php for($i=1;$i<=2;$i++)
                                        { ?>
                                    <th >Item Name</th>
                                    <th >Packing Item</th>
                                    <th >Quantity</th>
                                    <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $i = 0;
                                    echo ' <tr>';
                                    foreach($items as $item)
                                    {
                                        $i++;
                                        echo '<td>'.$item['name'].'</td>';
                                        echo '<td>'.$item['wraps'].'</td>';
                                        echo '<td>'.$item['qty'].'</td>';
                                    
                                        if($i == 2) 
                                        {
                                            echo '</tr><tr>';
                                            $i = 0;
                                        }
                                    }
                                    echo '</tr>';
                                
                                ?>
                                
                                </tbody>
                                </table>
                            
                           
                                
                        </div>
                        </div>
                        <!-- End -->
                        <p style="margin-left: 3%;">Charges and costing details</p>

                        <!-- charges and costing details -->
                            <div class="container">
                                <div class="row table-row calculation_table">

                                    <table class="table ">
                                        <tbody>
                                            <tr>
                                                <td colspan="5">Sub Total</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">&#8377 {{$order->total}}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="5">Discount</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">&#8377 {{$order->discount}}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="5">Discount Total</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">&#8377 <?php echo $order->sub_total - $order->discount;?></td>
                                            </tr>
                                            
                                            <tr colspan="3">
                                                <td >GST</td>
                                                <td class="text-right" colspan="3">CGST - <?php echo $order->gst / 2;?>%</td>
                                                <td class="text-right" colspan="5">&#8377 <?php echo $order->gst_amt / 2;?></td>
                                                
                                            </tr>
                                            <tr rowspan="3">
                                            <td></td>
                                            <td class="text-right" colspan="3">SGST - <?php echo $order->gst / 2;?>%</td>
                                            <td class="text-right" colspan="5">&#8377 <?php echo $order->gst_amt / 2;?></td>
                                            </tr>

                                            <tr rowspan="3">
                                                <td >Transport GST</td>
                                                <td class="text-right" colspan="3">CGST - <?php echo $order->transport_gst / 2;?>%</td>
                                                <td class="text-right" colspan="5">&#8377 <?php echo $order->transport_gst_amt / 2;?></td>
                                            </tr>

                                            <tr rowspan="3">
                                            <td></td>
                                            <td class="text-right" colspan="3">SGST - <?php echo $order->transport_gst / 2;?>%</td>
                                            <td class="text-right" colspan="5">&#8377 <?php echo $order->transport_gst_amt / 2;?></td>
                                            </tr>

                                            <tr>
                                                <td colspan="5">Grand Total</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">&#8377 {{$order->sub_total}}</td>
                                            </tr>

                                            

                                            <tr>
                                                <td colspan="5">Advance Payment</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">&#8377 {{$order->advance_payment}}</td>
                                            </tr>

                                            <tr>
                                                <th colspan="5">Pending Amount</th>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">&#8377 {{$order->pending_amt}}</td>
                                            </tr>
                                            
                                            
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        <!-- End -->

                        <div class="container">
                            <div class="row">
                            <div class="col-xs-6 margintop">
                                <p class=""><strong>Remark</strong></p>
                                <p style="margin-left: 3%;">Move Safely and Gracefully.</p>
                                
                                <p class=""><strong>Terms and Conditions</strong></p>
                                <div style="margin-left: 2%;">
                                    <p>Please keep your cash, Jewellery in your custody.</p>
                                    <p>- This rate is not to be Mathadi and union charges.</p>
                                    <p>- Pune juridiction</p>
                                    <p>- In case of goods damage in transportation, we are not responsible</p>
                                </div><br/>
                                <p>Assuring you of best of our services, we look forward to your confirmation.</p>
                                        <br/>
                                <p>Sincerely Yours,</p>
                                <p>Sai Packers & Movers</p>
                            </div>
                            </div>

                        </div>

                        <div style="margin-bottom:200px;">

                        </div>

                    </div>
                    </div>
                </div>
        </div>
    </div>

   
    
    <!-- <button type="button" class="btn btn-primary" onClick="myFunction();" target="_blank">Print</button> -->
    

    </body>
</html>
<script>
    function myFunction() {
        window.print();
    window.location.href=APP_URL+"/order-list";
  }
</script>
<style>

body {
    margin-top:20px;
    background:#eee;
    font-family:Cambria;
    font-size : 15px;
}
.invoice p {
    font-size:1rem;
}

.calculation_table {
    border:1px solid #000;
}
.margintop {
    margin-top:25px;
}

/*Invoice*/
.invoice .sai_text h4{
    color:#211255;
    font-weight:bold;
}
.invoice .sai_text span{
    color:#211255;

}

.invoice .origin_add {
    height: 70px;
    border: 1px solid #000;
}
.invoice .destination_add {
    height: 70px;
    border: 1px solid #000;
}
.invoice .customer_email {
    text-align:right;
    padding-top:10px;
}

.invoice .user_info {
    color: #fff;
    width: 100%;
    background-color: #e67817 !important;
    font-weight: 500;
}
.invoice .user_name {
    position: relative;
    left: 2%;
    font-size: 1rem;
    top: 4%;

}
.invoice .user_contact {
    position: relative;
    left: 5%;
    font-size: 1rem;
    top: 4%;

}
.invoice .user_invoiceid {
    position: relative;
    left: 17%;
    font-size: 1rem;
    top: 4%;

}
.invoice .user_date {
    position: relative;
    left: 24%;
    font-size: 1rem;
    top: 4%;

}
.invoice .from , .to{
    margin: 0px auto;
}
.invoice .saipackers_logo {
   
    max-width: 100%;
}
.invoice .top-left {
    font-size:65px;
	color:#3ba0ff;
    border-right: 3px solid #e35f14;

}

.invoice .top-right {
	padding-top:20px;
}

.invoice .table-row {
	margin-left:-15px;
	margin-right:-15px;
	margin-top:25px;
    border-bottom: 1px solid #615d5a;
    border-right: 1px solid #615d5a;
    border-left: 1px solid #615d5a;
}

.invoice .payment-info {
	font-weight:500;
}

.invoice .table-row .table>thead {
	border-top:1px solid #ddd;
}

.invoice .table-row .table>thead>tr>th {
	border-bottom:none;
}

.invoice .table>tbody>tr>td {
	padding:4px 20px;
}

.invoice .invoice-total {
	margin-right:-10px;
	font-size:16px;
}

.invoice .last-row {
	border-bottom:1px solid #ddd;
}

.invoice-ribbon {
	width:85px;
	height:88px;
	overflow:hidden;
	position:absolute;
	top:-1px;
	right:14px;
}

.ribbon-inner {
	text-align:center;
	-webkit-transform:rotate(45deg);
	-moz-transform:rotate(45deg);
	-ms-transform:rotate(45deg);
	-o-transform:rotate(45deg);
	position:relative;
	padding:7px 0;
	left:-5px;
	top:11px;
	width:120px;
	background-color:#66c591;
	font-size:15px;
	color:#fff;
}

.ribbon-inner:before,.ribbon-inner:after {
	content:"";
	position:absolute;
}

.ribbon-inner:before {
	left:0;
}

.ribbon-inner:after {
	right:0;
}

@media(max-width:575px) {
	.invoice .top-left,.invoice .top-right,.invoice .payment-details {
		/* text-align:center; */
        
	}

	.invoice .from,.invoice .to,.invoice .payment-details {
		float:none;
		width:100%;
		text-align:center;
		margin-bottom:25px;
	}

	.invoice p.lead,.invoice .from p.lead,.invoice .to p.lead,.invoice .payment-details p.lead {
		font-size:22px;
	}

	.invoice .btn {
		margin-top:10px;
	}


    
}

@media only screen and (min-device-width: 200px) and (max-device-width: 1024px)  {
      /* For portrait layouts only */
      
      
      .invoice .top-right 
      {
       width:60%;

      }


    }

@media print {
	.invoice {
		width:900px;
		height:800px;
	}
}
</style>