@extends('layouts.dashboard')
@section('dashcontent')

          <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card bg-white">
                  <div class="card-body d-flex align-items-center justify-content-between">
                    <h4 class="mt-1 mb-1">Hi, Welcomeback Super Admin!</h4>
                  </div>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card border-0 border-radius-2 bg-success">
                <div class="card-body">
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                    <div class="icon-rounded-inverse-success icon-rounded-lg">
                      <i class="mdi mdi-arrow-top-right"></i>
                    </div>
                    <div class="text-white">
                      <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Total Sales</p>
                      <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{@$total_sales}}</h3>
                        <!-- <small class="mb-0">This month</small> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card border-0 border-radius-2 bg-info">
                <div class="card-body">
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                    <div class="icon-rounded-inverse-info icon-rounded-lg">
                      <i class="mdi mdi-basket"></i>
                    </div>
                    <div class="text-white">
                      <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Total Items</p>
                      <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{@$total_items}}</h3>
                        <!-- <small class="mb-0">This month</small> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card border-0 border-radius-2 bg-danger">
                <div class="card-body">
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                    <div class="icon-rounded-inverse-danger icon-rounded-lg">
                      <i class="mdi mdi-chart-donut-variant"></i>
                    </div>
                    <div class="text-white">
                      <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Total Orders</p>
                      <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{@$total_orders}}</h3>
                        <!-- <small class="mb-0">This month</small> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card border-0 border-radius-2 bg-warning">
                <div class="card-body">
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                    <div class="icon-rounded-inverse-warning icon-rounded-lg">
                      <i class="mdi mdi-chart-multiline"></i>
                    </div>
                    <div class="text-white">
                      <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Total Customers</p>
                      <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{@$total_customers}}</h3>
                        <!-- <small class="mb-0">This month</small> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    

          <div class="row">
            <div class="col-xl-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-md-end flex-wrap">
                    <p class="card-title">All Today Registered Users</p>
                    <!-- <div class="dropdown mb-3 mb-md-0">
                      <button class="btn btn-sm btn-outline-light dropdown-toggle text-dark" type="button" id="dropdownMenuDate1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        2018
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate1" x-placement="top-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(76px, -2px, 0px);">
                        <a class="dropdown-item" href="#">2015</a>
                        <a class="dropdown-item" href="#">2016</a>
                        <a class="dropdown-item" href="#">2017</a>
                        <a class="dropdown-item" href="#">2018</a>
                      </div>
                    </div> -->
                  </div>
                  <div class="table-responsive">
                    <table class="table tickets-table mb-2">
                      <thead>
                        <tr><th class="text-muted pl-0">
                          Name
                        </th>                        
                        <th class="text-muted">
                          Email
                        </th>
                        <th class="text-muted">
                          Mobile 
                        </th>
                        <th class="text-muted">
                          Store Name
                        </th>
                      </tr></thead>
                      <tbody>
                        @if(!$custome_data->isEmpty())
                         @foreach($custome_data as $list)
                        <tr>
                          <td>
                            <p class="mb-0">{{$list->name}}</p>                           
                          </td>
                          <td>
                            <p class="mb-0">{{$list->email}}</p>
                          </td>
                          <td>
                            <p class="mb-0">{{$list->mobile_no}}</p>
                          </td>
                          <td>
                            <p class="mb-0">{{$list->store}}</p>
                          </td>
                          <td class="pr-0">
                            <i class="mdi mdi-dots-horizontal icon-sm cursor-pointer"></i>
                          </td>
                        </tr>
                        @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>  
              </div>
            </div>            
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Invoices</p>                 
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr class="border-top-0">
                          <th class="text-muted">Invoice</th>
                          <th class="text-muted">Name</th>
                          <th class="text-muted">Email</th>
                          <th class="text-muted">Best Price</th>
                          <th class="text-muted">Store Name</th>
                        </tr>
                      </thead>
                      <tbody>
                      @if(!$order_data->isEmpty())
                         @foreach($order_data as $list)
                        <tr>
                          <td>{{$list->id}}</td>
                          <td>{{$list->name}}</td>
                          <td>{{$list->email}}</td>
                          <td>{{$list->total_amount}}</td> 
                          <!-- <td><div class="badge badge-success badge-fw">{{@$list->status}}</div></td> -->
                          <td>{{$list->store}}</td>   
                        </tr>
                        @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        

@endsection
