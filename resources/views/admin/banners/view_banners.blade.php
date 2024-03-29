@extends('layouts.adminLayout.admin_design')
@section('content')
<div id="content">
  <div id="content-header">
  <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Banners</a> <a href="#" class="current">View banners</a> </div>
    <h1>Banners</h1>
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
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">

        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-picture"></i></span>
            <h5>View banners</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Banner ID</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Link</th>
                  <th>Image</th>
                  <th>Active</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              @foreach($banners as $banner)
                <tr class="gradeX">
                  <td>{{$banner->id}}</td>
                  <td>{{$banner->title}}</td>
                   <td>{{$banner->description}}</td>
                  <td>{{$banner->link}}</td>
                  <td>
                      @if(!empty($banner->image))
                       <img src="{{asset('/images/frontend_images/banners/'.$banner->image)}}" style="width:150px;">
                       @endif
                  </td>
                  <td>
                    @if($banner->status == 0)
                        <span style="color:red">Inactive</span>
                    @else 
                        <span style="color:green">Active</span>
                    @endif
                  </td>
                     
                  <td style="max-width:40px;" class="center">
                    <a style="width:90%;"  href="#myModal{{$banner->id}}" data-toggle="modal" class="btn btn-success btn-mini" title="View">View</a>
                    <a style="width:90%;" href="{{ url('/admin/edit-banner/'.$banner->id)  }} " class="btn btn-primary btn-mini" title="Edit">Edit</a>
                    <a style="width:90%;" rel="{{ $banner->id }}" rel1="delete-banner"  href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a>
                  </td> 
                </tr>
                <div id="myModal{{$banner->id}}" class="modal hide">
                  <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">×</button>
                    <h3><b>{{$banner->title}}</b></h3>
                  </div>
                  <div class="modal-body">
                    <p><b>Banner ID: </b> {{$banner->id}}</p>
                    <p><b>Title: </b> {{$banner->title}}</p>
                    <p><b>Description: </b> {{$banner->description}}</p>
                    <p><b>Link: </b> {{$banner->link}}</p>
                    <p><b>Status: </b>
                      @if($banner->status == 0)
                        Inactive
                      @else 
                        Active
                      @endif
                    </p>
                  </div>
                </div>
                @endforeach
                 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection