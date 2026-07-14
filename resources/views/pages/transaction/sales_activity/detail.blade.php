@extends('layouts.app')
@section('title')
Rakomsis Sales Activity - {{ $sales_activity->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Sales Activity</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Sales Activity
                    </a>
                    @if(sizeof($sales_activity->marketing_material) > 0)
                        <a href="{{ url($email_url) }}" class="btn  btn-primary pull-right text-white">
                            <i class="fa fa-at"></i> Send to email
                        </a>
                        <a onclick="print('{{ url($print_url) }}')" class="btn  btn-info pull-right text-white">
                            <i class="fa fa-print"></i> Print
                        </a>
                    @endif
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Status</td>
                                <td>
                                    {{ $sales_activity->status->name }}
                                    @if($sales_activity->discard_or_cancel_reason != null)
                                        <br>{{ $sales_activity->discard_or_cancel_reason }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td>{{ $sales_activity->code }}</td>
                            </tr>
                            <tr>
                                <td>Prospect</td>
                                <td>
                                    @if($sales_activity->source_status == "prospect")
                                        {{ $sales_activity->prospect->code }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                <td>{{ $sales_activity->employee->name }}</td>
                            </tr>
                            <tr>
                                <td>Customer</td>
                                <td>{{ $sales_activity->customer->name }}</td>
                            </tr>
                            <tr>
                                <td>Contact</td>
                                <td>
                                @if($sales_activity->contact_id != null)
                                    {{ $sales_activity->contact->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td>{!! $sales_activity->notes !!}</td>
                            </tr>
                            <tr>
                                <td>Marketing Material</td>
                                <td>
                                    @foreach($sales_activity->marketing_material as $marketing_material)
                                        @if($marketing_material->file_type == "jpg" || $marketing_material->file_type == "png")
                                            <img class="img img-responsive" src="{{ asset($marketing_material->file_path) }}" alt="">
                                        @else
                                            <a href="{{ $marketing_material->file_path }}" target="_blank">{{ $marketing_material->name }}</a>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Activity to this customer</td>
                                <td>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Date</td>
                                                <td>Contact</td>
                                                <td>Type</td>
                                                <td>Meeting Point</td>
                                                <td>Notes</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sales_activity->customer->sales_activity as $item)
                                                @php
                                                    $class="";
                                                    if($sales_activity->id == $item->id){
                                                        $class = "class=table-danger";
                                                    }
                                                @endphp
                                                <tr {{ $class }}>
                                                    <td>{{ date('j M Y', strtotime($item->created_at)) }}</td>
                                                    <td>{{ $item->contact->name }}</td>
                                                    <td>{{ $item->type }}</td>
                                                    <td>{{ $item->location }}</td>
                                                    <td>{{ $item->notes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
function print(link){
        var printWindow = window.open(link);
        var printAndClose = function () {
            if (printWindow.document.readyState == 'complete') {
                clearInterval(sched);
                printWindow.print();
                printWindow.close();
            }
        }
        var sched = setInterval(printAndClose, 800);
    }
</script>
@endsection
