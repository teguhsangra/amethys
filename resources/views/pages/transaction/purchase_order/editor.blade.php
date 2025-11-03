@extends('layouts.app')
@section('title')
Rakomsis Purchase Order - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">
                    Purchase Order Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Purchase Order
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }} 
                    <input type="hidden" name="status_name" id="status_name">
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Main Agreement</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('booking_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($purchase_order))
                                    <input type="text" class="form-control" value="{{$purchase_order->booking->code}} : {{$purchase_order->booking->customer->name}}" readonly>
                                    <input type="hidden" name="booking_id" value="{{$purchase_order->booking_id}}">
                                @else
                                <select class="selectpicker form-control" name="booking_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">      
                                    <option disabled selected>Select Your Option</option>
                                    @foreach($main_agreements as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($purchase_order)){
                                                if($purchase_order->booking_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->code }} : {{ $detail->customer->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('booking_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Vendor</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('vendor_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($purchase_order))
                                    <input type="text" class="form-control" value="{{$purchase_order->vendor->name}}" readonly>
                                    <input type="hidden" name="vendor_id" value="{{$purchase_order->vendor_id}}">
                                @else
                                <select class="selectpicker form-control" name="vendor_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">      
                                    <option disabled selected>Select Your Option</option>
                                    @foreach($vendors as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($purchase_order)){
                                                if($purchase_order->vendor_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('vendor_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Detail Transaction</label>
                        <div class="col-sm-10">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-primary text-center">
                                        <tr>
                                            <th>
                                                <a class="btn btn-success btn-round text-white" onclick="addDetail()"><i class="fa fa-plus"></i></a>
                                            </th>
                                            <th>Name</th>
                                            <th>Quantity</th>
                                            <th>Detail Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail_transaction">
                                    
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">Total Price</td>
                                            <td id="view_total_price" class="text-right"></td>
                                            <input type="hidden" id="total_price" name="total_price">
                                            <input type="hidden" id="total_tax_price" name="total_tax_price">
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Notes</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="notes" id="notes" placeholder="Notes..." @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($purchase_order)){{ $purchase_order->notes }}@endif</textarea>
                                <label class="error">{{ $errors->first('notes') }}</label>
                            </div>
                        </div>
                    </div>
                    @if(!empty(Request::get('action_status')))
                    <div class="row">
                        <input type="hidden" name="payment_status" value="PA">
                        <label class="col-sm-2 col-form-label">Payment Receipt</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="photo">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                        <div>
                                        <span class="btn btn-rose btn-round btn-file">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" name="photo" />
                                        </span>
                                        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                    </div>
                                </div>
                                <label class="error">{{ $errors->first('photo') }}</label>
                            </div>
                        </div>
                    </div>
                    @endif
                {{ Form::close() }}
            </div>
            <div class="card-footer">
                @if(!empty(Request::get('action_status')))
                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1 && $a_g_and_module->update == 1 && $a_g_and_module->isExec == 1)
                    <a onclick="continueTransaction('complete')" class="col-md-12 btn-lg btn btn-success">Complete</a>
                    @endif
                @else
                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1)
                    <a onclick="continueTransaction('open')" class="col-md-3 col-sm-offset-3 btn-lg btn btn-info">Save To Draft</a>
                    @endif


                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1 && $a_g_and_module->isExec == 1)
                    <a onclick="continueTransaction('posted')" class="col-md-4 col-sm-offset-1 btn-lg btn btn-default">Posting</a>
                    @endif
                @endif

                <div class="modal fade modal-mini modal-primary" id="continueTransactionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-small">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
                            </div>
                            <div class="modal-body text-center">
                                <p id="modal_label">Are you sure you want to do continue ?</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                                <button type="button" class="btn btn-success btn-link" onclick="submitForm('{{ $form_id }}')">Yes
                                    <div class="ripple-container"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var purchase_order_detail = new Array;
    @if(!empty($purchase_order))
        @foreach($purchase_order->purchase_order_detail as $purchase_order_detail)
            var detail = new Array;
            detail.name = '{{ $purchase_order_detail->name }}';
            detail.quantity = {{ $purchase_order_detail->quantity }};
            detail.detail_price = {{ $purchase_order_detail->detail_price }};

            purchase_order_detail.push(detail);
            
            loadTransaction();
        @endforeach
    @endif
    function continueTransaction(status_name){
        if(status_name == "open"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to save to draft this form, and you can edit this form further. <br> Are you sure want to continue ?";
        }else if(status_name == "posted"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to posting this form, and you can't edit this form anymore. <br> Are you sure want to continue ?";
        }else if(status_name == "complete"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to complete this form. <br> Are you sure want to continue ?";
        }else{

        }
        $("#continueTransactionModal").modal();
    }

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }

    function addDetail(){
        var detail = new Array;
        detail.name = '';
        detail.quantity = 1;
        detail.detail_price = 0;

        purchase_order_detail.push(detail);
        loadTransaction();
    }

    function removeDetail(i){
        purchase_order_detail.splice(i, 1); 
        loadTransaction();
    }

    function setName(i, name){
        purchase_order_detail[i].name = name;
        loadTransaction();
    }

    function changeQuantity(i, quantity){
        purchase_order_detail[i].quantity = quantity;
        loadTransaction();
    }

    function changeDetailPrice(i){
        var detail_price = $("#detail_price_"+i).val();
        purchase_order_detail[i].detail_price = detail_price;
        loadTransaction();
    }

    function loadTransaction(){
        var detail_transaction = '';
        var total_price = 0;
        for(var i=0; i < purchase_order_detail.length; i++){
            var sub_total = purchase_order_detail[i].quantity * purchase_order_detail[i].detail_price;
            total_price = total_price + sub_total;
            detail_transaction += '<tr>';
            detail_transaction += '<td class="text-center"><a class="btn btn-danger btn-round text-white" onclick="removeDetail('+i+')"><i class="fa fa-trash"></i></a></td>';
            detail_transaction += '<td><input type="text" name="name[]" class="form-control" value="'+purchase_order_detail[i].name+'" onchange="setName('+i+', this.value)"></td>';
            detail_transaction += '<td><input type="number" name="quantity[]" class="form-control text-center" id="quantity" value="'+purchase_order_detail[i].quantity+'" placeholder="Input quantity..." onchange="changeQuantity('+i+', this.value);"></td>';
            detail_transaction +=   '<td>'
                                        +'<input type="text" id="format_detail_price_'+i+'" class="form-control text-center" value="'+numberWithCommas(purchase_order_detail[i].detail_price)+'" onchange="changeToCurrencyFormat('+"'format_detail_price_"+i+"'"+','+"'detail_price_"+i+"'"+');changeDetailPrice('+i+');">'
                                        +'<input type="hidden" id="detail_price_'+i+'" name="detail_price[]" value="'+purchase_order_detail[i].detail_price+'">'+
                                    '</td>';
            detail_transaction += '<td id="sub_total" class="text-right">'+numberWithCommas(sub_total)+'</td>';
            detail_transaction += '</tr>';
        }
        
        document.getElementById("detail_transaction").innerHTML = detail_transaction;
        document.getElementById("total_price").value = total_price;
        document.getElementById("view_total_price").innerHTML = numberWithCommas(total_price);
    }
</script>
@endsection