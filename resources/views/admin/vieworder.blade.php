@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.ViewOrder') }} <small> {{ trans('labels.ViewOrder') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/orders')}}"><i class="fa fa-dashboard"></i>  {{ trans('labels.ListingAllOrders') }}</a></li>
      <li class="active"> {{ trans('labels.ViewOrder') }}</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="invoice" style="margin: 15px;">
      <!-- title row -->
      @if(session()->has('message'))
       <div class="col-xs-12">
       <div class="row">
      	<div class="alert alert-success alert-dismissible">
           <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
           <h4><i class="icon fa fa-check"></i> {{ trans('labels.Successlabel') }}</h4>
            {{ session()->get('message') }}
        </div>
        </div>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="col-xs-12">
      	<div class="row">
        	<div class="alert alert-warning alert-dismissible">
               <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
               <h4><i class="icon fa fa-warning"></i> {{ trans('labels.WarningLabel') }}</h4>
                {{ session()->get('error') }}
            </div>
          </div>
         </div>
        @endif
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header" style="padding-bottom: 25px; margin-top:0;">
            <i class="fa fa-globe"></i> 
			<span>{{ trans('labels.OrderID') }}# {{ $data['orders_data'][0]->orders_id }} </span>&nbsp;&nbsp;&nbsp;&nbsp;
			<span>Purchase Order# {{ $data['orders_data'][0]->order_no }} </span>&nbsp;&nbsp;&nbsp;&nbsp;
            <small style="display: inline-block">{{ trans('labels.OrderedDate') }}: {{ date('d/m/Y', strtotime($data['orders_data'][0]->date_purchased)) }}</small>
            <a href="{{ URL::to('admin/invoiceprint/'.$data['orders_data'][0]->orders_id)}}" target="_blank"  class="btn btn-default pull-right"><i class="fa fa-print"></i> {{ trans('labels.Print') }}</a> 
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        {{-- <div class="col-sm-4 invoice-col">
          {{ trans('labels.CustomerInfo') }}:
          <address>
            <strong>{{ $data['orders_data'][0]->customers_name }}</strong><br>
            {{ $data['orders_data'][0]->customers_street_address }} <br>
            {{ $data['orders_data'][0]->customers_city }}, {{ $data['orders_data'][0]->customers_state }} {{ $data['orders_data'][0]->customers_postcode }}<br>
            {{ trans('labels.Phone') }}: {{ $data['orders_data'][0]->customers_telephone }}<br>
            {{ trans('labels.Email') }}: {{ $data['orders_data'][0]->email }}
          </address>
        </div> --}}
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <strong>Shipping Info</strong>
          <address>
            {{ $data['orders_data'][0]->delivery_name }}<br>
            {{ $data['orders_data'][0]->delivery_company }} <br>
            {{ $data['orders_data'][0]->delivery_street_address }} <br>
            {{ $data['orders_data'][0]->delivery_city }} {{ $data['orders_data'][0]->delivery_suburb }} {{ $data['orders_data'][0]->delivery_state }} {{ $data['orders_data'][0]->delivery_postcode }}<br>
            
            <strong>Email: </strong>{{ $data['orders_data'][0]->email }} <br>
            <strong>{{ trans('labels.Phone') }}: </strong>{{ $data['orders_data'][0]->delivery_phone }}<br>
           {{-- <strong> {{ trans('labels.ShippingMethod') }}:</strong> {{ $data['orders_data'][0]->shipping_method }} <br>
           <strong> {{ trans('labels.ShippingCost') }}:</strong> @if(!empty($data['orders_data'][0]->shipping_cost)) {{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->shipping_cost }} @else --- @endif <br> --}}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col" hidden>
         {{ trans('labels.BillingInfo') }} 
          <address>
            <strong>{{ $data['orders_data'][0]->billing_name }}</strong><br>
            {{ $data['orders_data'][0]->billing_company }} <br>
            {{ $data['orders_data'][0]->billing_street_address }} <br>
            {{ $data['orders_data'][0]->billing_city }} {{ $data['orders_data'][0]->billing_suburb }} {{ $data['orders_data'][0]->billing_state }} {{ $data['orders_data'][0]->billing_postcode }}<br>
            <strong>{{ trans('labels.Phone') }}: </strong>{{ $data['orders_data'][0]->billing_phone }}<br>
          </address>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>{{ trans('labels.Qty') }}</th>
              <th>{{ trans('labels.ProductName') }}</th>
              <th>SKU</th>
              <th>{{ trans('labels.Price') }}</th> 
            </tr>
            </thead>
            <tbody>
            
            <?php 
			 $i=0;
			$orderTotal=0;
			foreach($data['orders_data'][0]->data as $products) {
			$orderTotal +=$products->final_price;
			?>
            
            <tr>
                <td width="50">{{  $products->products_quantity }}</td>
                <td>{{  $products->products_name }}<br></td>
                <td> {{ $products->sku }}</td>
                <td>{{ $data['currency'][19]->value }}{{ $products->final_price }}</td> 
             </tr>
            <?php } $i++; ?>
			<tr>
                <td ></td>
                <td><br></td>
                <td> <strong>Order total</strong></td>
                <td>$<?php  echo number_format($orderTotal,2); ?></td> 
             </tr>
            
            </tbody>
          </table>
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->

      <div class="row">
    {!! Form::open(array('url' =>'admin/updateOrder', 'method'=>'post', 'onSubmit'=>'return cancelOrder();', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
     {!! Form::hidden('orders_id', $data['orders_data'][0]->orders_id, array('class'=>'form-control', 'id'=>'orders_id'))!!}
     {!! Form::hidden('old_orders_status', $data['orders_data'][0]->orders_status_id, array('class'=>'form-control', 'id'=>'old_orders_status'))!!}
     {!! Form::hidden('customers_id', $data['orders_data'][0]->customers_id, array('class'=>'form-control', 'id'=>'device_id')) !!}
        <div class="col-xs-12">
        <hr>
          <p class="lead">{{ trans('labels.ChangeStatus') }}:</p>
          
            <div class="col-md-12">
              <div class="form-group">
                <!-- <label>{{ trans('labels.PaymentStatus') }}:</label> -->
                <label>Order Status:</label>
                <select class="form-control select2" id="status_id" name="orders_status" style="width: 100%;">
               	 @foreach( $data['orders_status'] as $orders_status)
                  <option value="{{ $orders_status->orders_status_id }}" @if( $data['orders_data'][0]->orders_status_id == $orders_status->orders_status_id) selected="selected" @endif >{{ $orders_status->orders_status_name }}</option>
                 @endforeach
                </select>
                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ChooseStatus') }}</span>
              </div>
            </div>
            <div class="col-md-12" style="display:none;">
               <div class="form-group">
                <label>{{ trans('labels.Comments') }}:</label>
                {!! Form::textarea('comments',  '', array('class'=>'form-control', 'id'=>'comments', 'rows'=>'4'))!!}
                <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CommentsOrderText') }}</span>
              </div>
            </div>
        </div>
         <!-- this row will not appear when printing -->
            <div class="col-xs-12">
              <a href="{{ URL::to('admin/orders')}}" class="btn btn-default"><i class="fa fa-angle-left"></i> {{ trans('labels.back') }}</a>
              <button type="submit" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> {{ trans('labels.Submit') }} </button>
              <!--<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                <i class="fa fa-download"></i> Generate PDF
              </button>-->

         <br><br> <hr><br>

            </div>
          {!! Form::close() !!}
        <div class="col-xs-12 hide">
          <p class="lead">Signature </p>
          <p><img style="max-height: 300px" alt="" src="{{ URL::to($data['orders_data'][0]->orders_image) }}" /></p>
        </div>
        <div class="col-xs-12">
          <p class="lead">Special Instructions</p>
            <?php 
            //echo "<pre>".$data['orders_status_history'][0]->comments; print_r($data['orders_status_history']); echo "</pre>".__FILE__.":".__LINE__;
            ?>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  {{-- <th>Delivery Date</th> --}}
                  {{-- <th>{{ trans('labels.Status') }}</th> --}}
                  <th>Delivery Note</th>
                  {{-- <th>{{ trans('labels.Comments') }}</th> --}}
                </tr>
              </thead>
              <tbody>
                @foreach( $data['orders_status_history'] as $orders_status_history)
                    <tr>
                        <!-- <td>
            							<?php 
            								// $date = new DateTime($orders_status_history->date_added);
            								// $status_date = $date->format('d-m-Y');
            								// //print $status_date;

                            // echo $data['orders_data'][0]->delivery_date;
            							?>
                        </td> -->
                        {{-- <td>
                        	@if($orders_status_history->orders_status_id==1)
                            	<span class="label label-warning">
                            @elseif($orders_status_history->orders_status_id==2)
                                <span class="label label-success">
                            @elseif($orders_status_history->orders_status_id==3)
                                 <span class="label label-danger">
                            @else
                                 <span class="label label-primary">
                            @endif
                            {{ $orders_status_history->orders_status_name }}
                                 </span>
                        </td> --}}
                        <td>
                          <?php 
                            echo $data['orders_data'][0]->delivery_note;
                          ?>
                        </td>
                        {{-- <td style="text-transform: initial;">{{ $orders_status_history->comments }}</td> --}}
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

     
    </section>
  <!-- /.content --> 
</div>
@endsection 