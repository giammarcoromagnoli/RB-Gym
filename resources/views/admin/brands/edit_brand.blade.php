@extends('layouts.adminlayout.admin_design')
@section('content')
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Brands</a> <a href="#" class="current">Edit brand</a> </div>
    <h1>Brands</h1>
    @if(Session::has('flash_message_error'))
      <div class="alert alert-error alert-block">
    	  <button type="button" class="close" data-dismiss="alert">×</button>
        <strong> {!! session ('flash_message_error') !!}</strong>
      </div>
    @endif
    @if(Session::has('flash_message_success'))
      <div class="alert alert-success alert-block">
    	  <button type="button" class="close" data-dismiss="alert">×</button>
        <strong> {!! session ('flash_message_success') !!}</strong>
      </div>
    @endif
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon icon-star"></i> </span>
            <h5>Edit brand</h5>
          </div>
          <div class="widget-content nopadding">
            <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{url('/admin/edit-brand/'.$brandDetails->name)}}" name="edit_brand" id="edit_brand" novalidate="novalidate"> {{csrf_field()}}
              
              <div class="control-group">
                <label class="control-label">Name</label>
                <div class="controls">
                  <input type="text" name="name" id="name" value="{{ $brandDetails->name }}">
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Description</label>
                <div class="controls">
                   <textarea class="textarea_admin" name="description" id="description">{{$brandDetails->description }}</textarea>
                </div>
              </div>
              
                <div class="control-group">
                    <label class="control-label">Logo</label>
                    <div class="controls">
                    <input type="file" name="logo" id="logo">
                        @if(!empty($brandDetails->logo))
                            <input type="hidden" name="current_logo" value="{{ $brandDetails->logo }}">
                        @endif
                    </div>
                </div>
            
              <div class="form-actions">
                <input type="submit" value="Edit brand" class="btn btn-success">
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid">

  </div>
</div>


@endsection