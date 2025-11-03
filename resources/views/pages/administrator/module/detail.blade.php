@extends('layouts.app')
@section('title')
Rakomsis Access Group - {{ $module->name }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Access Group</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Access Group
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td colspan="2">Name</td>
                                <td colspan="2">{{ $module->name }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Icon</td>
                                <td colspan="2">{{ $module->icon }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Link</td>
                                <td colspan="2">{{ $module->link }}</td>
                            </tr>
                            <tr>
                                <td>Parent</td>
                                <td>
                                @if($module->parent_id != null)
                                    {{ $module->this_parent->name }}
                                @endif
                                </td>
                                <td>Child</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                        @foreach($module->this_child as $this_child)
                                            <tr>
                                                <td>{{ $this_child->name }}</td>
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