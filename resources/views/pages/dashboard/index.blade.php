@extends('layouts.app')
@section('title')
Dashboard
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">calendar_today</i>
                </div>
                <h4 class="card-title">Filter</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label>Location</label>
                            <br>
                            <select id="location_id" class="selectpicker form-control" data-style="btn btn-primary btn-round" data-show-subtext="true" data-live-search="true" onchange="filterData()">
                                <option value="" disabled selected>Select Your Location </option>
                                @foreach ($location_id as $item)
                                    <option value="{{$item->id}}" >{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label>Year</label>
                            <input type="number" name="selection_year" id="selection_year" class="form-control" value="{{ date('Y') }}" onchange="getData()">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label>Months</label>
                            <select id="selection_month" class="selectpicker form-control" data-style="btn btn-primary btn-round" data-show-subtext="true" onchange="getData()">
                                <option value="" disabled selected>Select Your Option</option>
                                @foreach ($months as $item)
                                    @php
                                    $selected = '';
                                    if($date  == $item['number']){
                                        $selected = 'selected';
                                    }
                                @endphp
                                    <option value="{{$item['number']}}" {{ $selected}}>{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people_alt</i>
                </div>
                <h4 class="card-title">Customer</h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6">
                        <h3>Total Customer</h3>
                        <h3 id="total_customer">{{ number_format($total_customer, 0, ',', '.') }}</h3>
                    </div>
                    <div class="col-md-6">
                        <h3>Total Active Customer</h3>
                        <h3 id="total_active_customer">{{ number_format($total_active_customer, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <h5>Total VO <br>(Per Center Per Month)</h5>
                        <h5 id="total_vo_customer"></h3>
                    </div>
                    <div class="col-md-4">
                        <h5>Total SO <br>(Per Center Per Month)</h5>
                        <h5 id="total_so_customer"></h3>
                    </div>
                    <div class="col-md-4">
                        <h5>Total WS <br>(Per Center Per Month)</h5>
                        <h5 id="total_ws_customer"></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="card card-chart">
            <div class="card-header card-header-icon card-header-danger">
                <div class="card-icon">
                    <i class="material-icons">pie_chart</i>
                </div>
                <h4 class="card-title">Serviced Office Occupancy</h4>
            </div>
            <div class="card-body">
                <div class="flot-chart">
                    <div class="flot-chart-content" id="flot-pie-chart"></div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="card-category">Legend</h6>
                    </div>
                    <div class="col-md-12">
                        <i class="fa fa-circle" style="color: #ef5350;"></i> Occupied
                        <i class="fa fa-circle" style="color: #4caf50;"></i> Avaliability
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header card-header-icon card-header-danger">
                <div class="card-icon">
                    <i class="material-icons">pie_chart</i>
                </div>
                <h4 class="card-title">Total Sales Monthly</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6" id="vo_monthly_content"></div>
                    <div class="col-md-6" id="so_monthly_content"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-default card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">meeting_room</i>
                </div>
                <h4 class="card-title">Serviced Office</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables" id="serviced_office">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="display:none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-default card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">meeting_room</i>
                </div>
                <h4 class="card-title">Hotel</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables" id="hotel_room">


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script>

    function filterData(){
        getData();
    }

    function occupancy_chart(occupied_sqm, availability_sqm, occupied_per, availability_per){
        var data = [{
            label: "Occupied<br>"+occupied_sqm+" SQM",
            data: occupied_per,
            color: '#ef5350'
        }, {
            label: "Avaliability<br>"+availability_sqm+" SQM",
            data: availability_per,
            color: '#4caf50'
        }];

        var plotObj = $.plot($("#flot-pie-chart"), data, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    label: {
                        show: true,
                        radius: 2 / 3,
                        formatter: function (label, series) {
                            return '<div style="text-align:center;padding:2px;color:#000;">' + label + '<br/>' + series.data[0][1] + '%</div>';
                        },
                        threshold: 0.1
                    }
                }
            },
            grid: {
                hoverable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false
            }
        });
    }

    function getData(){
        var year = document.getElementById("selection_year").value;
        var months = document.getElementById("selection_month").value;
        var location_id = document.getElementById("location_id").value;
        var link_occupancy = "{{ url('getDataBooking') }}";
        var link_total_booking_monthly = "{{ url('getTotalBookingPerMonth') }}";
        var link_occupancy_graph = "{{ url('getOccupancyGraph') }}";
        var link_total_customer = "{{ url('getTotalCustomer') }}";
        var occupied_sqm = 0;
        var availability_sqm = 100;
        var occupied_per = 0;
        var availability_per = 100;

        // Start : Url Global
        // Start : Url For Loading : Occupany Table
        var so_url = link_occupancy+"?year="+year+"&months="+months+"&location_id="+location_id+"&room_category_id=1&show=monthly";
        var mr_url = link_occupancy+"?year="+year+"&months="+months+"&location_id="+location_id+"&room_category_id=2&show=hourly";
        var cw_url = link_occupancy+"?year="+year+"&months="+months+"&location_id="+location_id+"&room_category_id=3&show=hourly";
        var hotel_url = link_occupancy+"?year="+year+"&months="+months+"&location_id="+location_id+"&room_category_id=4&show=daily";
        // End : Url For Loading : Occupany Table

        // Start : Url For Loading : Total Booking Monthly
        var vo_monthly_url = link_total_booking_monthly+"?year="+year+"&location_id="+location_id+"&type=product";
        var so_monthly_url = link_total_booking_monthly+"?year="+year+"&location_id="+location_id+"&type=room&room_category_id=1";
        // Start : Url For Loading : Total Booking Monthly

        // Start : Url For Loading : Occupancy Graph
        var graph_url = link_occupancy_graph+"?year="+year+"&months="+months+"&location_id="+location_id+"&room_category_id=1";
        // End : Url For Loading : Occupancy Graph

        // Start : Url For Loading : Total Customer
        var total_customer_url = link_total_customer+"?year="+year+"&months="+months+"&location_id="+location_id;
        // End : Url For Loading : Total Customer
        // End : Url Global

        // Start : Content
        // Start : Content For Loading : Occupany Table
        var serviced_office = "";
        var meeting_room = "";
        var coworking = "";
        var hotel_room = "";
        // End : Content For Loading : Occupany Table

        // Start : Content For Loading : Total Booking Monthly
        var vo_monthly_content = "";
        var so_monthly_content = "";
        // End : Content For Loading : Total Booking Monthly
        // End : Content

        if(months != '' && location_id != ''){
            // Start : Ajax
            // Start : Ajax For Loading : Occupany Table
            $.get(so_url, function (data){
                serviced_office += '<table class="table table-striped table-bordered table-hover table-responsive" cellspacing="0" width="100%" style="max-height:250px;" id="serviced_office_table">';
                    serviced_office += '<thead>';
                        serviced_office +='<tr>';
                            serviced_office += '<th style="background-color: blue;color:white;">Room / Month</th>';
                            for(var i=0; i < data.months.length; i++){
                                serviced_office += '<th>'+data.months[i]+'</th>';
                            }
                        serviced_office +='</tr>';
                    serviced_office += '</thead>';
                    serviced_office += '<tbody>';
                    for(var j=0; j < data.rooms.length; j++){
                        serviced_office +='<tr>';
                            serviced_office += '<td class="table-info">'+data.rooms[j]['room_number']+'</td>';
                            for(var i=0; i < data.months.length; i++){
                                var detail = data.list[i][j];

                                if(detail == null || detail == ''){
                                    serviced_office += '<td></td>';
                                }else{
                                    serviced_office += '<td class="bg-warning">'+detail+'</td>';
                                }
                            }
                        serviced_office +='</tr>';
                    }
                    serviced_office += '</tbody>';
                serviced_office +='</table>';


                document.getElementById("serviced_office").innerHTML = serviced_office;
                $('#serviced_office_table').DataTable( {
                    "ordering": false
                } );
            });
            // $.get(mr_url, function (data){
            //     meeting_room += '<table class="table table-striped table-bordered table-hover table-responsive" cellspacing="0" width="100%" style="max-height:250px;" id="meeting_room_table">';
            //         meeting_room += '<thead>';
            //             meeting_room +='<tr>';
            //                 meeting_room += '<th style="background-color: blue;color:white;">Meeting Room Today</th>';
            //                 for(var i=0; i < data.hours.length; i++){
            //                     meeting_room += '<th>'+data.hours[i]+'</th>';
            //                 }
            //             meeting_room +='</tr>';
            //         meeting_room += '</thead>';
            //         meeting_room += '<tbody>';
            //         for(var j=0; j < data.rooms.length; j++){
            //             meeting_room +='<tr>';
            //                 meeting_room += '<td class="table-info">'+data.rooms[j]['room_number']+'</td>';
            //                 for(var i=0; i < data.hours.length; i++){
            //                     var detail = data.list[i][j];

            //                     if(detail == null || detail == ''){
            //                         meeting_room += '<td></td>';
            //                     }else{
            //                         meeting_room += '<td class="bg-warning">'+detail+'</td>';
            //                     }
            //                 }
            //             meeting_room +='</tr>';
            //         }
            //         meeting_room += '</tbody>';
            //     meeting_room +='</table>';


            //     document.getElementById("meeting_room").innerHTML = meeting_room;
            //     $('#meeting_room_table').DataTable( {
            //         "ordering": false
            //     } );
            // });
            // $.get(cw_url, function (data){
            //     coworking += '<table class="table table-striped table-bordered table-hover table-responsive" cellspacing="0" width="100%" style="width:100%;max-height:250px;" id="coworking_table">';
            //         coworking += '<thead>';
            //             coworking +='<tr>';
            //                 coworking += '<th style="background-color: blue;color:white;">Coworking Today</th>';
            //                 for(var i=0; i < data.hours.length; i++){
            //                     coworking += '<th>'+data.hours[i]+'</th>';
            //                 }
            //             coworking +='</tr>';
            //         coworking += '</thead>';
            //         coworking += '<tbody>';
            //         for(var j=0; j < data.rooms.length; j++){
            //             coworking +='<tr>';
            //                 coworking += '<td class="table-info">'+data.rooms[j]['room_number']+'</td>';
            //                 for(var i=0; i < data.hours.length; i++){
            //                     var detail = data.list[i][j];

            //                     if(detail == null || detail == ''){
            //                         coworking += '<td></td>';
            //                     }else{
            //                         coworking += '<td class="bg-warning">'+detail+'</td>';
            //                     }
            //                 }
            //             coworking +='</tr>';
            //         }
            //         coworking += '</tbody>';
            //     coworking +='</table>';


            //     document.getElementById("coworking").innerHTML = coworking;
            //     $('#coworking_table').DataTable( {
            //         "ordering": false
            //     } );
            // });
            $.get(hotel_url, function (data){
                hotel_room += '<table class="table table-striped table-bordered table-hover table-responsive" cellspacing="0" width="100%" style="width:100%;max-height:250px;" id="hotel_table">';
                    hotel_room += '<thead>';
                        hotel_room +='<tr>';
                            hotel_room += '<th style="background-color: blue;color:white;">Day / Room</th>';
                            // for(var i=0; i < data.rooms.length; i++){
                            //     hotel_room += '<th>'+data.rooms[i]['room_number']+'</th>';
                            // }
                            for(var i=0; i < data.days.length; i++){
                                hotel_room += '<th>'+data.days[i]+'</th>';
                            }
                        hotel_room +='</tr>';
                    hotel_room += '</thead>';
                    hotel_room += '<tbody>';
                    for(var j=0; j < data.rooms.length; j++){
                        hotel_room +='<tr>';
                            hotel_room += '<td class="table-info">'+data.rooms[j]['room_number']+'</td>';
                            for(var i=0; i < data.days.length; i++){
                                var detail = data.list[i][j];

                                if(detail == null || detail == ''){
                                    hotel_room += '<td></td>';
                                }else{
                                    hotel_room += '<td class="bg-warning">'+detail+'</td>';
                                }
                            }
                        hotel_room +='</tr>';
                    }
                    hotel_room += '</tbody>';
                hotel_room +='</table>';


                document.getElementById("hotel_room").innerHTML = hotel_room;
                $('#hotel_table').DataTable( {
                    "ordering": false
                } );
            });
            // End : Ajax For Loading : Occupany Table

            // Start : Ajax For Loading : Total Booking Monthly
            $.get(vo_monthly_url, function (data){
                vo_monthly_content += '<h3>Virtual Office</h3>';
                vo_monthly_content += '<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" id="vo_monthly_table">';
                    vo_monthly_content += '<thead>';
                        vo_monthly_content +='<tr>';
                            vo_monthly_content += '<th style="background-color: blue;color:white;"><b>Month</b></th>';
                            vo_monthly_content += '<th style="background-color: blue;color:white;"><b>Total</b></th>';
                        vo_monthly_content +='</tr>';
                    vo_monthly_content += '</thead>';
                    vo_monthly_content += '<tbody>';
                    for(i=0; i < data.months.length; i++){
                        vo_monthly_content +='<tr>';
                            vo_monthly_content +='<td>'+data.months[i]+'</td>';
                            vo_monthly_content +='<td>'+data.list[i]+'</td>';
                        vo_monthly_content +='</tr>';
                    }
                    vo_monthly_content += '</tbody>';
                vo_monthly_content +='</table>';


                document.getElementById("vo_monthly_content").innerHTML = vo_monthly_content;
            });
            $.get(so_monthly_url, function (data){
                so_monthly_content += '<h3>Serviced Office</h3>';
                so_monthly_content += '<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" id="so_monthly_table">';
                    so_monthly_content += '<thead>';
                        so_monthly_content +='<tr>';
                            so_monthly_content += '<th style="background-color: blue;color:white;"><b>Month</b></th>';
                            so_monthly_content += '<th style="background-color: blue;color:white;"><b>Total</b></th>';
                        so_monthly_content +='</tr>';
                    so_monthly_content += '</thead>';
                    so_monthly_content += '<tbody>';
                    for(i=0; i < data.months.length; i++){
                        so_monthly_content +='<tr>';
                            so_monthly_content +='<td>'+data.months[i]+'</td>';
                            so_monthly_content +='<td>'+data.list[i]+'</td>';
                        so_monthly_content +='</tr>';
                    }
                    so_monthly_content += '</tbody>';
                so_monthly_content +='</table>';


                document.getElementById("so_monthly_content").innerHTML = so_monthly_content;

            });
            // End : Ajax For Loading : Total Booking Monthly

            // Start : Ajax For Loading : Occupancy Graph
            $.get(graph_url, function (data){
                occupied_sqm = data.occupied_sqm;
                availability_sqm = data.availability_sqm;
                occupied_per = data.occupied_per;
                availability_per = data.availability_per;
                occupancy_chart(occupied_sqm, availability_sqm, occupied_per, availability_per)
            });
            // End : Ajax For Loading : Occupancy Graph

            // Start : Ajax For Loading : Total Customer
            $.get(total_customer_url, function (data){
                document.getElementById("total_vo_customer").innerHTML = data['total_vo_customer'];
                document.getElementById("total_so_customer").innerHTML = data['total_so_customer'];
                document.getElementById("total_ws_customer").innerHTML = data['total_ws_customer'];
            });
            // End : Ajax For Loading : Total Customer
            // End : Ajax
        }
    }
</script>
@endsection
