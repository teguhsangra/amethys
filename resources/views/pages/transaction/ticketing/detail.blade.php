@extends('layouts.app')
@section('title')
Rakomsis Ticketing -
@endsection
@section('content')
<style>
.imagePreview {
        width: 300px;
        height: 200px;
        background-position: center center;
    background:url(http://cliquecities.com/assets/no-image-e3699ae23f866f6cbdf8ba2443ee5c4e.jpg);
    background-color:#fff;
        background-size: cover;
    background-repeat:no-repeat;
        display: inline-block;
    box-shadow:0px -3px 6px 2px rgba(0,0,0,0.2);
    }
    .button-primar
    {
    width: 300px;
    display:block;
    border-radius:0px;
    box-shadow:0px 4px 6px 2px rgba(0,0,0,0.2);
    margin-top:-5px;
    }
    .imgUp
    {
    margin-bottom:15px;
    }
    .del
    {
    position:absolute;
    top:0px;
    right:15px;
    width:30px;
    height:30px;
    text-align:center;
    line-height:30px;
    background-color:rgba(255,255,255,0.6);
    cursor:pointer;
    }
    .imgAdd
    {
    width:30px;
    height:30px;
    border-radius:50%;
    background-color:#4bd7ef;
    color:#fff;
    box-shadow:0px 0px 2px 1px rgba(0,0,0,0.2);
    text-align:center;
    line-height:30px;
    margin-top:0px;
    cursor:pointer;
    font-size:15px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Ticketing</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Ticketing
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <i class="fa fa-pencil"></i> Balasan
                    </div>
                    <div class="col-md-6 text-right">
                        <i class="fa fa-plus"></i>
                        <i class="fa fa-minus" style="display:none;"></i>
                    </div>
                </div>
            </div>
            {{ Form::open(array('url' => $url_reply, 'method' => $method, 'enctype' => 'multipart/form-data')) }}
            <div class="card-body" id="formReply" style="display:none;">
                <input type="hidden" name="ticketing_id" value="{{ $id }}">
                @if(Auth::user()->id == $ticketing->user_id)
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                @else
                <input type="hidden" name="employee_id" value="{{ Auth::user()->id }}">
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <p class="single-form-row">
                            <label>Remarks</label>
                            <span style="color: red">{{ $errors->first('remarks') }}</span>
                            <textarea type="text" id="mytextarea" class="form-control" placeholder="Isi keluhan anda disini" name="remarks">{!! old('remarks') !!}</textarea>
                        </p>
                    </div>

                </div>
            </div>
            <div class="card-footer" id="submitForm" style="display:none;">
                <div class="col-md-12 text-center">
                    <a href="{{ url('ticketing') }}" class="col-md-4  btn-md btn btn-warning">Back</a>
                    <button type="submit" class="col-md-6 btn btn-md btn-primary">Send</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="background-color:#007bff;color:#fff;">
                <div class="row">
                    <div class="col-md-6 text-left">
                        {{ $ticketing->user->name }}
                        <br>
                        {{$ticketing->code}}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ date("j F Y", strtotime($ticketing->created_at)) }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-borderless"widht="100%">
                            <tbody>
                                <tr>
                                    <td width="10%">Subject</td>
                                    <td width="5%">:</td>
                                    <td >
                                        @if($ticketing->subject == null)
                                        {{$ticketing->ticketing_subject->name}}

                                        @else
                                        {{$ticketing->subject}}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <p>{!! $ticketing->remarks !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
@foreach($ticketing->ticketing_replies as $reply)
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"  @if($reply->user_id == null) style="background-color:#dc3545;color:#fff;"@else style="background-color:#007bff;color:#fff;"@endif>
                <div class="row">
                    <div class="col-md-6 text-left">
                        @if($reply->user_id == null)
                        {{$reply->employee->name}}
                        @else
                        {{ $reply->user->name }}
                        @endif
                    </div>
                    <div class="col-md-6 text-right">
                        {{ date("j F Y h:i:s", strtotime($reply->created_at)) }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>{!! $reply->remarks !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endforeach
@endsection

@section('js')
<script>
$(".fa-plus").on("click", function() {
$('.fa-minus').show();
$('.fa-plus').hide();
$('#formReply').show();
$('#submitForm').show();


});

$(".fa-minus").on("click", function() {
$('.fa-minus').hide();
$('.fa-plus').show();

$('#formReply').hide();
$('#submitForm').hide();

});
$(function() {
    $(document).on("change",".uploadFile", function()
    {
    	var uploadFile = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function(){ // set image data as background of div
                //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
            uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
            }
        }

    });
});


</script>
@endsection
