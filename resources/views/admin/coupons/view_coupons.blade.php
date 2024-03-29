@extends('layouts.adminLayout.admin_design')
@section('content')
<div id="content">
  <div id="content-header">
  <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Coupons</a> <a href="#" class="current">View coupons</a> </div>
    <h1>Coupons</h1>
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
          <div class="widget-title"> <span class="icon"><i class="icon-gift"></i></span>
            <h5>View coupons</h5>
          </div>
          <div class="widget-content nopadding">
           <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Coupon ID</th>
                  <th>Coupon code</th>
                  <th>Amount</th>
                  <th>Expiry date</th>
                  <th>Used</th>
                  <th>Creation date</th>
                  <th>Active</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              @foreach($coupons as $coupon)
                <tr class="gradeX">
                  <td>{{$coupon->id}}</td>
                  <td>{{$coupon->coupon_code}}</td>
                  <td>{{$coupon->amount}} €</td>
                  <td>{{$coupon->expiry_date}}</td>
                  <td>{{$coupon->used}}</td>
                  <td>{{$coupon->created_at}}</td>
                  <td>
                    @if($coupon->status == 0)
                        <span style="color:red">Inactive</span>
                    @else 
                        <span style="color:green">Active</span>
                    @endif
                  </td>
                  <td style="max-width:40px;" class="center">
                    <a style="width:80%;"  href="#myModal{{$coupon->id}}" data-toggle="modal" class="btn btn-success btn-mini" title="View">View</a>
                    <a style="width:80%;" href="{{ url('/admin/edit-coupon/'.$coupon->id)  }} " class="btn btn-primary btn-mini" title="Edit">Edit</a>
                    <a style="width:80%;"rel="{{ $coupon->id }}" rel1="delete-coupon" href="javascript:"  class="btn btn-danger btn-mini deleteRecord" title="Delete">Delete</a>
                  </td> 
                </tr>
                <div id="myModal{{$coupon->id}}" class="modal hide">
                  <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">×</button>
                    <h3><b>{{$coupon->coupon_code}}</b></h3>
                  </div>
                  <div class="modal-body">
                    <p><b>Coupon ID: </b> {{$coupon->id}}</p>
                    <p><b>Coupon code: </b> {{$coupon->coupon_code}}</p>
                    <p><b>Amount: </b> {{$coupon->amount}}</p>
                    <p><b>Expiry date: </b> {{$coupon->expiry_date}}</p>
                    <p><b>Used: </b> {{$coupon->used}}</p>
                    <p><b>Creation date: </b> {{$coupon->created_at}}</p>
                    <p><b>Status: </b> 
                      @if($coupon->status == 0)
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