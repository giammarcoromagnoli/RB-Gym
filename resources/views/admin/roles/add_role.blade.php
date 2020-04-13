@extends('layouts.adminlayout.admin_design')
@section('content')
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Gruppi</a> <a href="#" class="current">Aggiungi gruppo</a> </div>
    <h1>Gruppi</h1>
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
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Aggiungi gruppo</h5>
          </div>
          <div class="widget-content nopadding">
            <form class="form-horizontal" method="post" action="{{url('/admin/add-role')}}" name="add_role" id="add_role" > {{csrf_field()}}

            <div class="control-group">
                <label class="control-label">Nome gruppo</label>
                <div class="controls">
                  <input type="text" name="name" min="0" id="name" required>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Supervisore</label>
                <div class="controls">
                  <input type="text" name="guard_name" min="0" id="guard_name" required>
                </div>
              </div>


              <div class="form-actions">
                <input type="submit" value="Aggiungi gruppo" class="btn btn-success">
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