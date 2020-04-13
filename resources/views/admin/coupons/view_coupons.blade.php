@extends('layouts.adminLayout.admin_design')
@section('content')
<div id="content">
  <div id="content-header">
  <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Coupons</a> <a href="#" class="current">View coupons</a> </div>
    <h1>Coupons</h1>
    @if(Session::has('flash_message_error'))
    <div class="alert alert-success alert-block">
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
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View coupons</h5>
          </div>
          <div class="widget-content nopadding">
           <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Coupon ID</th>
                  <th>Coupon code</th>
                  <th>Amount</th>
                  <th>Amount type</th>
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
                    <?php if($coupon->amount_type == "Percentage") echo '<td>' .$coupon->amount. '% </td>' ?> 
                    <?php if($coupon->amount_type != "Percentage") echo '<td>' .$coupon->amount. '€ </td>'?>
                  <td>{{$coupon->amount_type}}</td>
                  <td>{{$coupon->expiry_date}}</td>
                  <td>{{$coupon->used}}</td>
                  <td>{{$coupon->created_at}}</td>
                  
                  
                  <?php if($coupon->status == 0) echo '<td>Inactive</td>'?> 
                  <?php if($coupon->status == 1) echo '<td>Active</td>'?>
                  <td class="center">
                    <a href="{{ url('/admin/edit-coupon/'.$coupon->id)  }} " class="btn btn-primary btn-mini" title="Edit">Edit</a>
                    <a rel="{{ $coupon->id }}" rel1="delete-coupon"   <?php /*href="{{ url('/admin/delete-coupon/'.$coupon->id)  }} "  */?> href="javascript:"  class="btn btn-danger btn-mini deleteRecord" title="Delete">Delete</a>
                  </td> 
                </tr>
                
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