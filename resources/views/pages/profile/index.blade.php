@extends('layouts.app')
@section('title')
Rakomsis Profile - {{ ucwords(Auth::user()->type) }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-header-icon card-header-success">
                <div class="card-icon">
                    <i class="material-icons">perm_identity</i>
                </div>
                <h4 class="card-title">Edit Profile -
                    <small class="category">Complete your profile</small>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => url($form_url), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Code</label>
                                <input type="text" class="form-control" name="code" value="{{ $employee->code }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="bmd-label-floating">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $employee->name }}">
                                <label class="error">{{ $errors->first('name') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Email address</label>
                                <input type="email" class="form-control" name="email" value="{{ $employee->email }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{ $employee->phone }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Role</label>
                                <input type="text" class="form-control" name="role" value="{{ $employee->role }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Department</label>
                                <input type="text" class="form-control" name="department" value="{{ $employee->department }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Bio</label>
                                <div class="form-group">
                                    <label class="bmd-label-floating">Write your own bio</label>
                                    <textarea class="form-control" rows="9" name="bio">{{ Auth::user()->bio }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="title">Avatar</h4>
                            <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                <div class="fileupload-new thumbnail">
                                    <img src="{{ asset('assets/img/default-avatar.png') }}" alt="photo" width="150">
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="width:150px;height:150px;"></div>
                                <div>
                                    <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                        <input type="file" name="photo" />
                                    </span>
                                    <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                        <i class="fa fa-times"></i> Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="form-group">
                                    <label class="bmd-label-floating">Fill it if you want to change password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success pull-right">Update Profile</button>
                    <div class="clearfix"></div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-profile">
            <div class="card-avatar">
                <a href="#pablo">
                @if(Auth::user()->photo == null)
                    <img class="img" src="{{ asset('assets/img/default-avatar.png') }}" />
                @else
                    <img class="img" src="{{ asset(Auth::user()->photo) }}" />
                @endif
                </a>
            </div>
            <div class="card-body">
                <h6 class="card-category text-gray">{{ ucwords(Auth::user()->type) }}</h6>
                <h4 class="card-title">{{ ucwords(Auth::user()->name) }}</h4>
                <p class="card-description">
                    {{ Auth::user()->bio }}
                </p>
                <a href="#pablo" class="btn btn-success btn-round">Follow</a>
            </div>
        </div>
    </div>
</div>
@endsection
