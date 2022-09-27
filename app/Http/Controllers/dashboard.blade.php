<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=9">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/iconfonts/mdi/font/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.addons.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-colorpicker@3.0.3/dist/css/bootstrap-colorpicker.min.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('images/logo-mini.png') }}" />
  <link rel="stylesheet" href="{{asset('css/custom.css')}}">
  <link rel="stylesheet" href="{{asset('css/toastr.css')}}">
  <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
  <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('vendors/js/vendor.bundle.addons.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.time.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.pie.min.js"></script>
  <script src='https://www.jqueryscript.net/demo/Customizable-Liquid-Bubble-Chart-With-jQuery-Canvas/waterbubble.js'></script>
  <script src="{{ asset('js/waterbubble.js') }}"></script>

</head>
<body >
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo" href="{{ url('/') }}">
        @if(!empty($logo)) 
        <img src="{{ asset('images/logo') }}/{{$logo }}" alt="logo"/>
        @else 
        <img src="{{ asset('images/logo/logo.png') }}" alt="logo"/>
        @endif
      </a>
      <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}"><img src="{{ asset('images/logo-mini.png') }}" alt="logo"/></a>
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
                <img src="{{ asset('images/faces/default.png') }}" alt="profile"/>
                <span class="nav-profile-name"> {{ Auth::user()->name }} </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="{{route('edit_user')}}">
                        <i class="mdi mdi-account-circle text-primary"></i>
                        Profile
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout text-primary"></i>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
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
          @role('admin')
          <li class="nav-item">
              <a class="nav-link" href="{{route('dashboard')}}">
                <span class="activebg">
                <i class="mdi mdi-home-outline menu-icon"></i>
                <span class="menu-title">Dashboard</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-details')}}">
                <span class="activebg">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">RCT Dashboard</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="{{route('user')}}">
                <span class="activebg">
                <i class="mdi mdi-account-outline menu-icon"></i>
                <span class="menu-title">Users</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('station')}}">
                <span class="activebg">
                <i class="mdi mdi-gas-station menu-icon"></i>
                <span class="menu-title">Station</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('csr')}}">
                <span class="activebg">
                <i class="mdi mdi mdi-file-outline menu-icon"></i>
                <span class="menu-title">Daily Report</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('trn')}}">
                <span class="activebg">
                <i class="mdi mdi-sale menu-icon"></i>
                <span class="menu-title">Transactions</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('loyalty')}}">
                <span class="activebg">
                <i class="mdi mdi-heart-outline menu-icon"></i>
                <span class="menu-title">User Loyalty</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('stations_loyalty')}}">
              <span class="activebg">
              <i class="mdi mdi-fuel mdi-rotate-315 menu-icon"></i>
              <span class="menu-title">Station Loyalty</span>
              </span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('stations_price')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">Change Price</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-list')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">RCT</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="{{route('tank')}}">
                <span class="activebg">
                <i class="mdi mdi-inbox"></i>
                <span class="menu-title">Tank</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('tank-trn')}}">
                <span class="activebg">
                <i class="mdi mdi-caravan menu-icon"></i>
                <span class="menu-title">Tank Trn</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('expense')}}">
                <span class="activebg">
                <i class="mdi mdi-file-document"></i>
                <span class="menu-title">Expense</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('periodic-report')}}">
                <span class="activebg">
                <i class="mdi mdi-calendar-clock"></i>
                <span class="menu-title">Periodic Report</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('stock')}}">
                <span class="activebg">
                <i class="mdi mdi-beer"></i>
                <span class="menu-title">Stock</span>
                </span>
              </a>
          </li>
          {{--  <li class="nav-item">
              <a class="nav-link" href="#">
                <span class="activebg">
                <i class="mdi mdi-email-outline menu-icon"></i>
                <span class="menu-title">Email </span>
                </span>
              </a>
          </li> --}}

          @endrole
          @role('lotto-manager')
          <li class="nav-item">
              <a class="nav-link" href="{{route('stock')}}">
                <span class="activebg">
                <i class="mdi mdi-beer"></i>
                <span class="menu-title">Stock</span>
                </span>
              </a>
          </li>          
          @endrole
          @role('owner')
          <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}">
              <span class="activebg">
              <i class="mdi mdi-home-outline menu-icon"></i>
              <span class="menu-title">Dashboard</span>
              </span>
            </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-details')}}">
                <span class="activebg">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">RCT Dashboard</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="{{route('csr')}}">
                <span class="activebg">
                <i class="mdi mdi mdi-file-outline menu-icon"></i>
                <span class="menu-title">Daily Report</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('trn')}}">
                <span class="activebg">
                <i class="mdi mdi-sale menu-icon"></i>
                <span class="menu-title">Transactions </span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('loyalty')}}">
                <span class="activebg">
                <i class="mdi mdi-heart-outline menu-icon"></i>
                <span class="menu-title">User Loyalty</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('stations_loyalty')}}">
              <span class="activebg">
              <i class="mdi mdi-fuel mdi-rotate-315 menu-icon"></i>
              <span class="menu-title">Station Loyalty</span>
              </span>
            </a>
          </li>
        <li class="nav-item">
              <a class="nav-link" href="{{route('stations_price')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">Change Price</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-list')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">RCT</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="{{route('tank')}}">
                <span class="activebg">
                <i class="mdi mdi-inbox"></i>
                <span class="menu-title">Tank</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('tank-trn')}}">
                <span class="activebg">
                <i class="mdi mdi-caravan menu-icon"></i>
                <span class="menu-title">Tank Trn</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('expense')}}">
                <span class="activebg">
                <i class="mdi mdi-file-document"></i>
                <span class="menu-title">Expense</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('periodic-report')}}">
                <span class="activebg">
                <i class="mdi mdi-calendar-clock"></i>
                <span class="menu-title">Periodic Report</span>
                </span>
              </a>
          </li>
          @endrole
          @role('manager')
          <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}">
              <span class="activebg">
              <i class="mdi mdi-home-outline menu-icon"></i>
              <span class="menu-title">Dashboard</span>
              </span>
            </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-details')}}">
                <span class="activebg">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">RCT Dashboard</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="{{route('csr')}}">
                <span class="activebg">
                <i class="mdi mdi mdi-file-outline menu-icon"></i>
                <span class="menu-title">Daily Report</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('trn')}}">
                <span class="activebg">
                <i class="mdi mdi-sale menu-icon"></i>
                <span class="menu-title">Transactions</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('loyalty')}}">
              <span class="activebg">
              <i class="mdi mdi-heart-outline menu-icon"></i>
              <span class="menu-title">User Loyalty</span>
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('stations_loyalty')}}">
              <span class="activebg">
              <i class="mdi mdi-fuel mdi-rotate-315 menu-icon"></i>
              <span class="menu-title">Station Loyalty</span>
              </span>
            </a>
          </li>
        <li class="nav-item">
              <a class="nav-link" href="{{route('stations_price')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">Change Price</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-list')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">RCT</span>
                </span>
              </a>
          </li> -->
          <li class="nav-item">
              <a class="nav-link" href="{{route('tank')}}">
                <span class="activebg">
                <i class="mdi mdi-inbox"></i>
                <span class="menu-title">Tank</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('tank-trn')}}">
                <span class="activebg">
                <i class="mdi mdi-caravan menu-icon"></i>
                <span class="menu-title">Tank Trn</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('expense')}}">
                <span class="activebg">
                <i class="mdi mdi-file-document"></i>
                <span class="menu-title">Expense</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('periodic-report')}}">
                <span class="activebg">
                <i class="mdi mdi-calendar-clock"></i>
                <span class="menu-title">Periodic Report</span>
                </span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{route('stock')}}">
                <span class="activebg">
                <i class="mdi mdi-beer"></i>
                <span class="menu-title">Stock</span>
                </span>
              </a>
          </li>
          @endrole
          @role('company')
          {{-- <li class="nav-item">
            <a class="nav-link" href="{{route('loyalty')}}">
              <span class="activebg">
              <i class="mdi mdi-heart-outline menu-icon"></i>
              <span class="menu-title">Loyalty </span>
              </span>
            </a>
          </li> --}}
          <li class="nav-item">
              <a class="nav-link" href="{{route('trn')}}">
                <span class="activebg">
                <i class="mdi mdi-sale menu-icon"></i>
                <span class="menu-title">Transactions</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-list')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">RCT</span>
                </span>
              </a>
          </li> -->
          @endrole
          @role('user')
          <li class="nav-item">
              <a class="nav-link" href="{{route('trn')}}">
                <span class="activebg">
                <i class="mdi mdi-sale menu-icon"></i>
                <span class="menu-title">Transactions</span>
                </span>
              </a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link" href="{{route('rct-list')}}">
                <span class="activebg">
                <i class="mdi mdi-coin menu-icon"></i>
                <span class="menu-title">RCT</span>
                </span>
              </a>
          </li> -->
          @endrole
          @role('accountant')
          <li class="nav-item">
              <a class="nav-link" href="{{route('expense')}}">
                <span class="activebg">
                <i class="mdi mdi-file-document"></i>
                <span class="menu-title">Expense</span>
                </span>
              </a>
          </li>
          @endrole
          @role('account-manager')
          <li class="nav-item">
              <a class="nav-link" href="{{route('expense')}}">
                <span class="activebg">
                <i class="mdi mdi-file-document"></i>
                <span class="menu-title">Expense</span>
                </span>
              </a>
          </li>
          @endrole         
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
            @yield('dashcontent')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2019. All rights reserved. </span>
            <span class="text-muted text-center text-sm-right d-block d-sm-inline-block">Memory Usage : 
            @php
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
            @endphp
            </span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

   <script src="{{ asset('js/toastr.js')}}"></script>
    <script type="text/javascript">
        var _token = '{{ csrf_token() }}';
        var APP_URL = {!! json_encode(url('/')) !!};
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    </script>


    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
     {{-- <script src="{{ asset('js/settings.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script> --}}

    <script src="{{ asset('js/toastDemo.js') }}"></script>
    <script src="{{ asset('js/tooltips.js') }}"></script>
    <script src="{{ asset('js/popover.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    {{-- <script src="{{ asset('js/formpickers.js') }}"></script> --}}
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/validatior.js') }}"></script>
    <script src="{{ asset('js/ajax-js.js') }}"></script>
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
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
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
    <script src="https://unpkg.com/jspdf-autotable@3.1.1/dist/jspdf.plugin.autotable.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</body>
</html>