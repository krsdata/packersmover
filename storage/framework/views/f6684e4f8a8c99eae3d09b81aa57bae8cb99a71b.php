<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=9">
  <title><?php echo e(config('app.name', 'Laravel')); ?></title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo e(asset('vendors/iconfonts/mdi/font/css/materialdesignicons.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('vendors/css/vendor.bundle.base.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('vendors/css/vendor.bundle.addons.css')); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-colorpicker@3.0.3/dist/css/bootstrap-colorpicker.min.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo e(asset('css/vertical-layout-light/style.css')); ?>">
  <link rel = "stylesheet" type = "text/css" media = "print" href = "<?php echo e(asset('css/mystyle.css')); ?>">
  <!-- endinject -->
  <link rel="shortcut icon" href="<?php echo e(asset('images/logo/logo-mini.png')); ?>" />
  <link rel="stylesheet" href="<?php echo e(asset('css/custom.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/toastr.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/sweetalert2.min.css')); ?>">
  

  <script src="<?php echo e(asset('vendors/js/vendor.bundle.base.js')); ?>"></script>
  <script src="<?php echo e(asset('vendors/js/vendor.bundle.addons.js')); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.time.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.pie.min.js"></script>
  <script src='https://www.jqueryscript.net/demo/Customizable-Liquid-Bubble-Chart-With-jQuery-Canvas/waterbubble.js'></script>
  <script src="<?php echo e(asset('js/waterbubble.js')); ?>"></script>
  <!-- ckeditor -->
  <script src="https://cdn.ckeditor.com/4.14.0/standard-all/ckeditor.js"></script>


</head>
<body class="sidebar-icon-only">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo" href="<?php echo e(url('/')); ?>">
        <?php if(!empty($logo) && file_exists(public_path('images/logo/'.$logo)) ): ?> 
        <img src="<?php echo e(asset('images/logo')); ?>/<?php echo e($logo); ?>" alt="logo"/>
        <?php else: ?> 
        <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="logo"/>
        <?php endif; ?>
      </a>
      <a class="navbar-brand brand-logo-mini" href="<?php echo e(url('/')); ?>"><!-- <img src="<?php echo e(asset('images/logo-mini.png')); ?>" alt="logo"/> -->
        <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="logo"/>
      </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
      </button>
      
      <ul class="navbar-nav mr-auto">
      </ul>

      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item nav-profile dropdown mr-0 mr-sm-2">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <img src="<?php echo e(asset('images/faces/default.png')); ?>" alt="profile"/>
                <span class="nav-profile-name"> <?php echo e(Auth::user()->name); ?> </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <?php if(Auth::user()->type == 'pos-admin'){?>
                <a class="dropdown-item" href="<?php echo e(route('edit_posadmin')); ?>">
                        <i class="mdi mdi-account-circle text-primary"></i>
                        Profile
                </a>
              <?php } else{?>
              <a class="dropdown-item" href="<?php echo e(route('edit_user')); ?>">
                        <i class="mdi mdi-account-circle text-primary"></i>
                        Profile
                </a>
              <?php } ?>
                <a class="dropdown-item" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout text-primary"></i>
                    Logout
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </li>
      </ul>
    </div>
  </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="theme-setting-wrapper" style="display:none">
        <div id="settings-trigger"><i class="mdi mdi-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close mdi mdi-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
        <?php if(auth()->check() && auth()->user()->hasRole('pos-admin')) : ?>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('posadmindashboard')); ?>">
                <span class="activebg">
                <i class="mdi mdi-view-dashboard"></i>
                <span class="menu-title">Dashboard</span>
                </span>
              </a>
          </li>  
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('vendors')); ?>">
                <span class="activebg">
                <i class="mdi mdi-account-multiple"></i>
                <span class="menu-title">Vendors</span>
                </span>
              </a>
          </li>  
          <?php endif; ?>
          <?php if(auth()->check() && auth()->user()->hasRole('admin')) : ?>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                <span class="activebg">
                 <i class="mdi mdi-view-dashboard"></i>
                <span class="menu-title">Dashboard</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('rct-details')); ?>">
                <span class="activebg">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">RCT Dashboard</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('user')); ?>">
                <span class="activebg">
                <i class="mdi mdi-account-outline menu-icon"></i>
                <span class="menu-title">Users</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('station')); ?>">
                <span class="activebg">
                <i class="mdi mdi-gas-station menu-icon"></i>
                <span class="menu-title">Station</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('csr')); ?>">
                <span class="activebg">
                <i class="mdi mdi mdi-file-outline menu-icon"></i>
                <span class="menu-title">Daily Report</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('trn')); ?>">
                <span class="activebg">
                <i class="mdi mdi-sale menu-icon"></i>
                <span class="menu-title">Transactions</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('loyalty')); ?>">
                <span class="activebg">
                <i class="mdi mdi-heart-outline menu-icon"></i>
                <span class="menu-title">User Loyalty</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('stations_loyalty')); ?>">
              <span class="activebg">
              <i class="mdi mdi-fuel mdi-rotate-315 menu-icon"></i>
              <span class="menu-title">Station Loyalty</span>
              </span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('stations_price')); ?>">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">Change Price</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('rct-list')); ?>">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">RCT</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('tank')); ?>">
                <span class="activebg">
                <i class="mdi mdi-inbox"></i>
                <span class="menu-title">Tank</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('tank-trn')); ?>">
                <span class="activebg">
                <i class="mdi mdi-caravan menu-icon"></i>
                <span class="menu-title">Tank Trn</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('expense')); ?>">
                <span class="activebg">
                <i class="mdi mdi-file-document"></i>
                <span class="menu-title">Expense</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('periodic-report')); ?>">
                <span class="activebg">
                <i class="mdi mdi-calendar-clock"></i>
                <span class="menu-title">Periodic Report</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('stock')); ?>">
                <span class="activebg">
                <i class="mdi mdi-elevation-rise"></i>
                <span class="menu-title">Stock</span>
                </span>
              </a>
          </li>
          

          <?php endif; ?>
          <?php if(auth()->check() && auth()->user()->hasRole('sai-manager')) : ?>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('posdashboard')); ?>">
                <span class="activebg">
                <i class="mdi mdi-view-dashboard"></i>
                <span class="menu-title">Dashboard</span>
                </span>
              </a>
          </li>  
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('users')); ?>">
                <span class="activebg">
                <i class="mdi mdi-account"></i>
                <span class="menu-title">User</span>
                </span>
              </a>
          </li>  
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('category')); ?>">
                <span class="activebg">
                <i class="mdi mdi-format-list-bulleted"></i>
                <span class="menu-title">Category</span>
                </span>
              </a>
          </li>     
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('order')); ?>">
                <span class="activebg">
                <i class="mdi mdi-codepen"></i>
                <span class="menu-title">Quotations</span>
                </span>
              </a>
          </li>    

          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('cale')); ?>">
                <span class="activebg">
                <i class="mdi mdi-file-outline"></i>
                <span class="menu-title">Calender</span>
                </span>
              </a>
          </li>    
          
          <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('ticket')); ?>">
                <span class="activebg">
                <i class="mdi mdi-file-outline"></i>
                <span class="menu-title">Ticket</span>
                </span>
              </a>
          </li>   
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('booking')); ?>">
                <span class="activebg">
                <i class="mdi mdi-deskphone"></i>
                <span class="menu-title">Order</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('feedback')); ?>">
                <span class="activebg">
                <i class="mdi mdi-file-document"></i>
                <span class="menu-title">Feedback</span>
                </span>
              </a>
          </li> 
        
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('contact')); ?>">
                <span class="activebg">
                <i class="mdi mdi-deskphone"></i>
                <span class="menu-title">Contact</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('faq')); ?>">
                <span class="activebg">
                <i class="mdi mdi-deskphone"></i>
                <span class="menu-title">Faq</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('privacy')); ?>">
                <span class="activebg">
                <i class="mdi mdi-deskphone"></i>
                <span class="menu-title">Privacy</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('terms')); ?>">
                <span class="activebg">
                <i class="mdi mdi-deskphone"></i>
                <span class="menu-title">Terms & Conditions</span>
                </span>
              </a>
          </li> -->
          <?php endif; ?>
         
        
          <?php if(auth()->check() && auth()->user()->hasRole('user')) : ?>
          <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('trn')); ?>">
                <span class="activebg">
                <i class="mdi mdi-sale menu-icon"></i>
                <span class="menu-title">Transactions</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('rct-list')); ?>">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">RCT</span>
                </span>
              </a>
          </li> -->
          <?php endif; ?>
         
         
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper pb-0" >
            <?php echo $__env->yieldContent('dashcontent'); ?>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <!-- <span class="text-muted text-center d-block d-sm-inline-block w-100">Copyright ©2021 Wevaluesoft. All rights reserved.</span> -->
           <!--  <span class="text-muted text-center text-sm-right d-block d-sm-inline-block">Memory Usage : 
            <?php
                $size = memory_get_usage();
                if ($size >= 1073741824) {
                  $fileSize = round($size / 1024 / 1024 / 1024,1) . 'GB';
                } elseif ($size >= 1048576) {
                    $fileSize = round($size / 1024 / 1024,1) . 'MB';
                } elseif($size >= 1024) {
                    $fileSize = round($size / 1024,1) . 'KB';
                } else {
                    $fileSize = $size . ' bytes';
                }
                echo $fileSize;
            ?>
            </span> -->
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

   <script src="<?php echo e(asset('js/toastr.js')); ?>"></script>
    <script type="text/javascript">
        var _token = '<?php echo e(csrf_token()); ?>';
        var APP_URL = <?php echo json_encode(url('/')); ?>;
        <?php if(Session::has('success')): ?>
            toastr.success("<?php echo e(Session::get('success')); ?>");
        <?php endif; ?>
        <?php if(Session::has('info')): ?>
            toastr.info("<?php echo e(Session::get('info')); ?>");
        <?php endif; ?>
        <?php if(Session::has('warning')): ?>
            toastr.warning("<?php echo e(Session::get('warning')); ?>");
        <?php endif; ?>
        <?php if(Session::has('error')): ?>
            toastr.error("<?php echo e(Session::get('error')); ?>");
        <?php endif; ?>
    </script>


    <script src="<?php echo e(asset('js/off-canvas.js')); ?>"></script>
    <script src="<?php echo e(asset('js/hoverable-collapse.js')); ?>"></script>
    <script src="<?php echo e(asset('js/template.js')); ?>"></script>
     

    <script src="<?php echo e(asset('js/toastDemo.js')); ?>"></script>
    <script src="<?php echo e(asset('js/tooltips.js')); ?>"></script>
    <script src="<?php echo e(asset('js/popover.js')); ?>"></script>
    <script src="<?php echo e(asset('js/sweetalert2.min.js')); ?>"></script>
    
    <script src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/validatior.js')); ?>"></script>
    <script src="<?php echo e(asset('js/ajax-js.js')); ?>"></script>
    <script src="<?php echo e(asset('js/html2canvas.min.js')); ?>"></script>
    <style type="text/css">
        .datepicker.datepicker-dropdown {
            padding: 0;
            width: 30%;
            max-width: 330px;
            min-width: 250px;
            z-index: 9999!important;
        }
        .from-date-pickerveiw-two .mdi.mdi-calendar {
            padding: 10px;
            color: #fff;
            background: #97012E;
            border: none;
        }
        .bs-tooltip-top{text-transform: capitalize;}
    </style>
    <script type="text/javascript">

      // costing script for 
      var pack,transport,loading,unloading,unpacking,ac,local,car_transport,insurance,gst,transport_gst,
      discount,sub_total,final_total;
      
      $(document).keyup("#gst", function(){
       var pack = $("#packing").val();
       var transport = $("#transport").val();
       var loading = $("#loading").val();
       var unloading = $("#unloading").val();
       var unpacking = $("#unpacking").val();
       var ac = $("#ac").val();
       var local = $("#local").val();
       var car_transport = $("#car_transport").val();
       var insurance = $("#insurance").val();
       var gst = $("#gst").val();

       if(pack == '' )
       {
          pack = '0';
       }
       if(transport == '')
       {
         transport = '0';
       }
       if(loading == '')
       {
         loading = '0';
       }
       if(unloading == '')
       {
         unloading = '0';
       }
       if(unpacking == '')
       {
         unpacking = '0';
       }
       if(ac == '')
       {
         ac = '0';
       }
       if(local == '')
       {
         local = '0';
       }
       if(car_transport == '')
       {
         car_transport = '0';
       }
       if(insurance == '')
       {
         insurance = '0';
       }

        if(gst)
        {

          sub_total = parseInt(pack) + parseInt(transport) + parseInt(loading) + parseInt(unloading)
          + parseInt(unpacking) + parseInt(ac) + parseInt(local) + parseInt(car_transport) + parseInt(insurance)
            ;
          // gst calculation
          // $("#total").val(Math.round(sub_total));
          // grand = sub_total  * gst / 100;
          // $("#gst_amt").val(Math.round(grand));
          // $("#sub_total").val(sub_total + grand);

          // new logic 
          $("#total").val(Math.round(sub_total));
          $("#sub_total").val(Math.round(sub_total));

        }
       
      });

      $(document).keyup("#transport_gst", function(){
        transport_gst = $("#transport_gst").val();
        if(transport_gst)
        {
          // transport gst calculation
          // var all_total = $("#sub_total").val();
          // trans_gst = all_total  * transport_gst / 100;
          // $("#transport_gst_amt").val(Math.round(trans_gst));
          // $("#sub_total").val(Math.round(all_total) + Math.round(trans_gst));
          // var discounttola = Math.round(all_total) + Math.round(trans_gst);
          // $("#gross_total").val(discounttola);

          var all_total = $("#sub_total").val();
          
        }
      });



       // discount
       $(document).keyup("#discount", function(){
        discount = $("#discount").val();
        var alltotals = $("#sub_total").val();
        var gst = $("#gst").val();
        transport_gst = $("#transport_gst").val();
          if(discount && alltotals)
          {
              var disc = alltotals - discount;
              grand = disc  * gst / 100;
              
              $("#gst_amt").val(Math.round(grand));

              trans_gst = disc  * transport_gst / 100;
              
              $("#transport_gst_amt").val(Math.round(trans_gst));

              $("#gross_total").val(Math.round(grand) + Math.round(trans_gst) + Math.round(disc));

              //$("#gross_total").val(disc);
          }
      });
      // advance payment
      $(document).keyup("#advance_payment", function(){
        ad_payment = $("#advance_payment").val();
        var allsone = $("#gross_total").val();
        if(ad_payment && allsone)
        {
            var addd =  allsone - ad_payment;
            $("#pending_amt").val(addd);
        }
        
      });

      // end

        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
            return true;
        }
        $(document).ready(function(){
          $('input[name="dates"]').daterangepicker({
              locale: {
                format: 'MMM DD, YYYY, HH:mm'
              },
              timePicker:true,
              timePicker24Hour:true
          });
          $('input[name="singledate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(new Date().getFullYear() + 50 )
          });
        })
    </script>
    <!-- <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script> -->
    <!-- <script src="https://unpkg.com/jspdf-autotable@3.1.1/dist/jspdf.plugin.autotable.js"></script> -->

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <script src="https://ninja11.in/assets/js/bootbox.js" type="text/javascript"></script>
    <script>
          
//delete record method
$('button[name="remove_levels"]').on('click', function(e){

//    bootbox.confirm('hello');
     var self = $(this);   
    var form = $(this).closest('form'); 
    e.preventDefault(); 
    
   bootbox.confirm('<b><h3>Are you sure you want to delete?</h3></b>',function(result){
	if(result)
	{
         var id = self.attr('id');
	    $('#deleteForm_'+id).submit();
	}   
 	
   });
});

      </script>
</body>
</html><?php /**PATH /var/www/html/app/resources/views/layouts/dashboard.blade.php ENDPATH**/ ?>