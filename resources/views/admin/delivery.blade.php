@extends('admin.layout')

@section('content')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link href="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.css" rel="stylesheet"/>
<link href="https://code.jquery.com/ui/1.12.1/themes/pepper-grinder/jquery-ui.css" rel="stylesheet"/>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js"></script>

<style>
#print-array {
    position:fixed;
    top:0;
    right:0;
    padding: 20px;
}
.datepicker-inline{
display:none !important;
}
</style>
<div class="content-wrapper"> 

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> {{ trans('labels.Setting') }}<small>{{ trans('labels.Setting') }}...</small> </h1>

    <ol class="breadcrumb">

       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>

      <li class="active">{{ trans('labels.Setting') }}</li>

    </ol>

  </section>

  

  <!-- Main content -->

  <section class="content">   

    

    <!-- /.row -->

    <div class="row">

      <div class="col-md-12">

        <div class="box">

          <div class="box-header">

            <h3 class="box-title">{{ trans('labels.delivery_setting') }}</h3>

          </div>

          

          <!-- /.box-header -->

          <div class="box-body">

            <div class="row">

              <div class="col-xs-12">

              		<div class="">

                        <!--<div class="box-header with-border">

                          <h3 class="box-title">Setting</h3>

                        </div>-->

                        <!-- /.box-header -->

                        <!-- form start -->                        

                         <div class="box-body">

                            <div class="row">

              <div class="col-xs-12">

              		<div class="box box-info">

                        <br>                       

                       	

                        @if(session()->has('message'))

                            <div class="alert alert-success" role="alert">

						  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                {{ session()->get('message') }}

                            </div>

                        @endif

                        

                        @if(session()->has('errorMessage'))

                            <div class="alert alert-danger" role="alert">

						  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                {{ session()->get('errorMessage') }}

                            </div>

                        @endif

                        

                        <!-- form start -->                        

                         <div class="box-body">

                            {!! Form::open(array('url' =>'admin/addnewdelivery', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}

                                   <div class="form-group">                                    

                                      <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.DeliveryDate') }}</label>

                                      <div class="col-sm-10 col-md-2">

                                        <input  class="form-control" id="datepickernew" type="text" name="delivery_dates" value="">     
                                      </div>

                                    </div>
                            

                              

                            

                              <!-- /.box-body -->

                            <div class="box-footer text-center">

                            	<button type="submit" class="btn btn-primary">{{ trans('labels.Update') }}</button>

                            	<a href="{{ URL::to('admin/dashboard/this_month')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>

                            </div>

                              

                              <!-- /.box-footer -->

                            {!! Form::close() !!}</div>

                  </div>

              </div>

            </div>

            

          </div>

          <!-- /.box-body --> 

        </div>

        <!-- /.box --> 

      </div>

      <!-- /.col --> 

    </div>

    <!-- /.row --> 

    

    <!-- Main row --> 

    

    <!-- /.row --> 

  </section>

  <!-- /.content --> 

</div>
  <script>
$("#datepickernew").multiDatesPicker({
   minDate: 0,
});
</script>
@endsection 