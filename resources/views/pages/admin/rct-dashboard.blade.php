@extends('layouts.dashboard')

@section('dashcontent')
<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="cust-card-title">
                    <i class="mdi mdi-home-outline cust-box-icon"></i>
                    RCT Dashboard
                </h4>
            </div>
            <div class="col-sm-6">
            <form name="user_search" id="user_search" class="search_filter_form" method="GET" accept-charset="UTF-8">
                            {{ csrf_field() }}

                <div class="form-group d-flex custom-search-view-place" style="margin-bottom:0px;border-radius: 0px 5px 5px 0px;">
                    @if($role_name == "admin")
                    <select class="form-control" placeholder="Station" name="search_input" id="search_input" data-msg-required="stations is required" required >
                        {{-- <option value="">Station</option> --}}
                        @if( isset($stations) && !empty($stations))
                        @foreach($stations as $station)
                            <option value="{{$station->id}}" @if (isset($search_input) && $search_input == $station->id ) selected="selected" @endif> {{$station->title}}</option>
                        @endforeach
                        @endif
                    </select>
                    @endif
                    <input type="text" class="form-control singledate" placeholder="Date" name="singledate"value="{{$singledate}}" />
                    <button   class="btn btn-primary common_search_filter" ><i class="mdi mdi-magnify "></i></button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>
</div>
<div class="row dashboard-div">
    <div class="col-md-4">
        <div class="row">
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card border-0 border-radius-10 bg-primary">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
                    <div class="icon-rounded-inverse-primary cust-icon-rounded-xs icon-rounded-xs">
                        <i class="mdi mdi mdi-cash-multiple"></i>
                    </div>
                    <div class="text-white ">
                        <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Total Amount</p>
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!! number_format((float)$data['total_amount'],$decimal_point,'.',',') !!}</h3>
                        <small class="mb-0">{{$currency_code}}</small>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card border-0 border-radius-10 bg-primary">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
                    <div class="icon-rounded-inverse-primary cust-icon-rounded-xs icon-rounded-xs">
                        <i class="mdi mdi mdi-transfer"></i>
                    </div>
                    <div class="text-white ">
                        <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">Transactions</p>
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!! number_format((float)$data['total_tran'],0,'.',',') !!}</h3>
                        <small class="mb-0">Count</small>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            @foreach($data['fuel'] as $fkey => $fvalue)
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card border-0 border-radius-10 " style="background:{!! $fvalue['color'] !!}">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
                    <div class="icon-rounded-inverse-danger cust-icon-rounded-xs icon-rounded-xs">
                        <i class="mdi mdi mdi-oil-temperature"></i>
                    </div>
                    <div class="text-white ">
                        <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">{{$fkey}}</p>
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!! number_format((float)$fvalue['ltr'],2,'.',',') !!}</h3>
                        <small class="mb-0">Ltr</small>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card border-0 border-radius-10 "  style="background:{!! $fvalue['color'] !!}">
                <div class="card-body">
                    <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between cust-card-dash">
                    <div class="icon-rounded-inverse-danger cust-icon-rounded-xs icon-rounded-xs">
                        <i class="mdi mdi mdi-oil-temperature"></i>
                    </div>
                    <div class="text-white ">
                        <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left text-uppercase">{{$fkey}}</p>
                        <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                        <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{!! number_format((float)$fvalue['amt'],$decimal_point,'.',',') !!}</h3>
                        <small class="mb-0">{{$currency_code}}</small>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                    <p class="card-title">Number of transactions per hours</p>
                </div>
                <div id="morris-area-example"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Number of transactions per day</h4>
            <div class="flot-chart-container" style="position:relative;">
                <div id="stacked-bar-chart" class="flot-chart"></div>
            </div>
        </div>
        </div>
    </div>
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Liters per day</h4>
            <div id="morris-bar-example"></div>
        </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Product share</h4>
            <div class="flot-chart-container">
                <div id="pie-chart" class="flot-chart"></div>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
var result = {!! $stran !!}
$(function() {
  'use strict';
    if ($('#morris-area-example').length) {
        Morris.Area({
        element: 'morris-area-example',
        resize: true,
        lineColors: {!! $slpdcolor !!},
        data: result,
        xkey: 'y',
        hideHover: 'auto',
        ykeys: {!! $slpdkey !!},
        labels: {!! $slpdlable !!}
        });
    }


    var stackedData = {!! json_encode($wtpd) !!};
    if ($("#stacked-bar-chart").length) {
      $.plot("#stacked-bar-chart", stackedData, {
        series: {
          stack: 0,
          lines: {
            show: false,
            fill: true,
            steps: false
          },
          bars: {
            show: true,
            fill: true,
            barWidth: 36000000
          },
        },
        xaxis: {
            mode: "time",
            timeBase: "seconds",
            timeformat: "%d/%m"
        },
        grid: {
          borderWidth: 0,
          labelMargin: 10,
          hoverable: true,
          clickable: false,
          mouseActiveRadius: 6,
        }
      });
    }
    $("#stacked-bar-chart").bind("plothover", function (event, pos, item) {
        if (item) {
             showTooltip(item);
        }else{
            $("#tooltip").hide();
        }
    });
    $("#placeholder").bind("plothovercleanup", function (event, pos, item) {
            $("#tooltip").hide();
    });

    $("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #ccc",
			padding: "2px",
			"background-color": "#fff",
			opacity: 0.80
        }).appendTo("body");

    function showTooltip(item){
        var div = '';
        div += '<span style="float:left;width:120px;font-size:12px;">';
        stackedData.forEach(element => {
            div += '<span style="color:'+element.color+'">'+element.label+' :' + element.data[item.dataIndex][1] + '</span><br />';
        });
        div += '</span>' ;
        // Creating and showing tooltip
        $("#tooltip").html(div)
						.css({top: item.pageY+5, left: item.pageX-50})
						.fadeIn(200);
    }


    if ($("#morris-bar-example").length) {
    var slpd = {!! $slpd !!}
    Morris.Bar({
        element: 'morris-bar-example',
        barColors: {!! $slpdcolor !!},
        data: slpd,
        xkey: 'y',
        hideHover: 'auto',
        ykeys: {!! $slpdkey !!},
        labels: {!! $slpdlable !!}
        });
    }


    var data = {!! $prodtla !!}

    if ($("#pie-chart").length) {
        $.plot("#pie-chart", data, {
            series: {
            pie: {
                show: true,
                radius: 1,
                label: {
                show: true,
                radius: 3 / 4,
                formatter: labelFormatter,
                background: {
                    opacity: 0.5
                }
                }
            }
            },
            legend: {
            show: false
            }
        });
    }

});

function labelFormatter(label, series) {
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>"+numberWithCommas(series.data[0][1].toFixed(2))+" ltr<br/>"+ Math.round(series.percent) + " %</div>";
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

</script>
@endsection
