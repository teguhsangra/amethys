@extends('layouts.print')
@section('content')
<br><br>
<div class="container" >
     <div class="row">
        <div class="col-md-6 text-left">

        </div>
        <div class="col-md-6 text-right">
            <img class="img-fluid pull-right" src="{{asset('company-logo.png')}}" width="200">
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-12 text-center">
            <p style="font-size:22px;"><b><u>SURAT KETERANGAN DOMISILI</u></b><br>
                <b>No. {{$booking->code}}</b>
            </p>
        </div>
    </div>
    <br>
    <br>
    <br>
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10 text-justify">
            <p style="font-size:18px;">Kami yang bertanda tangan dibawah ini bertindak atas nama {{$company_name}}:</p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10" style="font-size:18px;">
            <table >
                <tr>
                    <td class="text-left">Nama</td>
                    <td class="text-left">:</td>
                    <td class="text-right">&nbsp;{{$director_name}}</td>
                </tr>
                <tr>
                    <td class="text-left">Jabatan</td>
                    <td class="text-left">:</td>
                    <td class="text-left">&nbsp;Director of Operations</td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
            <p style="font-size:18px;">Dengan ini menerangkan bahwa,</p>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-12 text-center">
            <p><font size="5px"><b>{{$booking->customer->name}}</b></font><br>
            </p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10 text-center">
            <p style="font-size:18px;">adalah benar berdomisili di kantor bersama kami dengan alamat :
            </p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6 text-center">
            <p style="font-size:18px;">
                <b>{!!$booking->location->address !!}</b>
            </p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6 text-center">
            <p style="font-size:18px;">
                Masa berlaku surat domisili ini adalah terhitung dari,
                <br>
                <b>{{date("j F Y",strtotime($booking->start_date))}} sampai dengan {{date("j F Y",strtotime($booking->end_date))}}</b>
            </p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10 text-center">
            <p style="font-size:18px;">Demikian surat keterangan domisili ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10 text-center">
            <table width="100%" class="table table-borderless">
                <tr>
                    <td width="50%">
                        <p style="font-size:22px;">Jakarta, {{date("j F Y",strtotime($booking->signed_date))}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="font-size:22px;">{{$company_name}}</p>
                        @if($status == "sign")
                            <img src="{{asset('/uploads/sign/sign.png')}}" width="500px" height="200px">
                        @else
                            <br><br><br><br><br><br><br><br><br>
                        @endif

                    </td>
                </tr>
                <tr>
                    <td><p style="font-size:22px;"><b><u>{{$director_name}}</u></b></p></td>
                </tr>
                <tr>
                    <td><p style="font-size:22px;">Director of Operations</p></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
