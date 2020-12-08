@extends('admin.layout')
<style>
.wrapper.wrapper2{
	display: block;
}
.wrapper{
	display: none;
}
</style>
<body onload="window.print();">
<div class="wrapper wrapper2">
  <!-- Main content -->
  <section class="invoice" style="margin: 15px;">
      <!-- title row -->
      <div class="col-xs-12">
      <div class="row">
       @if(session()->has('message'))
      	<div class="alert alert-success alert-dismissible">
           <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
           <h4><i class="icon fa fa-check"></i> {{ trans('labels.Successlabel') }}</h4>
            {{ session()->get('message') }}
        </div>
        @endif
        @if(session()->has('error'))
        	<div class="alert alert-warning alert-dismissible">
               <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
               <h4><i class="icon fa fa-warning"></i> {{ trans('labels.WarningLabel') }}</h4>
                {{ session()->get('error') }}
            </div>
        @endif
        
        
       </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header" style="padding-bottom: 25px; margin-top:0;">            <i class="fa fa-globe"></i> 			<span>{{ trans('labels.OrderID') }}# {{ $data['orders_data'][0]->orders_id }} </span>&nbsp;&nbsp;&nbsp;&nbsp;			<span>Purchase Order# {{ $data['orders_data'][0]->order_no }} </span>&nbsp;&nbsp;&nbsp;&nbsp;            <small style="display: inline-block">{{ trans('labels.OrderedDate') }}: {{ date('d/m/Y', strtotime($data['orders_data'][0]->date_purchased)) }}</small>            <a href="{{ URL::to('admin/invoiceprint/'.$data['orders_data'][0]->orders_id)}}" target="_blank"  class="btn btn-default pull-right"><i class="fa fa-print"></i> {{ trans('labels.Print') }}</a>           </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info" >
        <div class="col-sm-4 invoice-col" hidden> 
          <strong>{{ trans('labels.CustomerInfo') }}</strong>
          <address>
            {{ $data['orders_data'][0]->customers_name }}<br>
            {{ $data['orders_data'][0]->customers_street_address }} <br>
            {{ $data['orders_data'][0]->customers_city }}, {{ $data['orders_data'][0]->customers_state }} {{ $data['orders_data'][0]->customers_postcode }}, {{ $data['orders_data'][0]->customers_country }}<br>
            {{ trans('labels.Phone') }}: {{ $data['orders_data'][0]->customers_telephone }}<br>
            {{ trans('labels.Email') }}: {{ $data['orders_data'][0]->email }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-12 invoice-col">
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
            {{ trans('labels.Phone') }}: {{ $data['orders_data'][0]->billing_phone }}<br>
            {{ $data['orders_data'][0]->billing_street_address }} <br>
            {{ $data['orders_data'][0]->billing_city }} {{ $data['orders_data'][0]->billing_postcode }} {{ $data['orders_data'][0]->billing_state }}<br>
          </address>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">        <div class="col-xs-12 table-responsive">          <table class="table table-striped">            <thead>            <tr>              <th>{{ trans('labels.Qty') }}</th>              <th>{{ trans('labels.ProductName') }}</th>              <th>SKU</th>              <th>{{ trans('labels.Price') }}</th>             </tr>            </thead>            <tbody>                        <?php 			 $i=0;			$orderTotal=0;			foreach($data['orders_data'][0]->data as $products) {			$orderTotal +=$products->final_price;			?>                        <tr>                <td width="50">{{  $products->products_quantity }}</td>                <td>{{  $products->products_name }}<br></td>                <td> {{ $products->sku }}</td>                <td>{{ $data['currency'][19]->value }}{{ $products->final_price }}</td>              </tr>            <?php } $i++; ?>			<tr>                <td ></td>                <td><br></td>                <td> <strong>Order total</strong></td>                <td>$<?php  echo number_format($orderTotal,2); ?></td>              </tr>                        </tbody>          </table>        </div>        <!-- /.col -->              </div>
      <!-- /.row -->

      <div class="row" hidden>
        <!-- accepted payments column -->
        <div class="col-xs-7">
          <p class="lead" style="margin-bottom:10px">{{ trans('labels.PaymentMethods') }}:</p>
          <p class="text-muted well well-sm no-shadow" style="text-transform:capitalize">
           	{{ str_replace('_',' ', $data['orders_data'][0]->payment_method) }}
          </p>
          @if(!empty($data['orders_data'][0]->coupon_code))
              <p class="lead" style="margin-bottom:10px">{{ trans('labels.Coupons') }}:</p>
                <table class="text-muted well well-sm no-shadow stripe-border table table-striped" style="text-align: center; ">
                	<tr>
                        <th style="text-align: center; ">{{ trans('labels.Code') }}</th>
                        <th style="text-align: center; ">{{ trans('labels.Amount') }}</th>
                    </tr>
                	@foreach( json_decode($data['orders_data'][0]->coupon_code) as $couponData)
                    	<tr>
                        	<td>{{ $couponData->code}}</td>
                            <td>{{ $couponData->amount}} 
                            	
                                @if($couponData->discount_type=='percent_product')
                                    ({{ trans('labels.Percent') }})
                                @elseif($couponData->discount_type=='percent')
                                    ({{ trans('labels.Percent') }})
                                @elseif($couponData->discount_type=='fixed_cart')
                                    ({{ trans('labels.Fixed') }})
                                @elseif($couponData->discount_type=='fixed_product')
                                    ({{ trans('labels.Fixed') }})
                                @endif
                            </td>
                        </tr>
                    @endforeach
				</table>                
          @endif
          
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-5">
          <!--<p class="lead"></p>-->

          <div class="table-responsive ">
            <table class="table order-table">
              <tr>
                <th style="width:50%">{{ trans('labels.Subtotal') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['subtotal'] }}</td>
              </tr>
              <tr>
                <th>{{ trans('labels.Tax') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->total_tax }}</td>
              </tr>
              <tr>
                <th>{{ trans('labels.ShippingCost') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->shipping_cost }}</td>
              </tr>
              @if(!empty($data['orders_data'][0]->coupon_code))
              <tr>
                <th>{{ trans('labels.DicountCoupon') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->coupon_amount }}</td>
              </tr>
              @endif
              <tr>
                <th>{{ trans('labels.Total') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->order_price }}</td>
              </tr>
            </table>
          </div>
              
        </div>  
        
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="row">           
        <div class="col-xs-12">
          <p class="lead" style="margin-bottom:10px">Special Instructions:</p>
          <p class="text-muted well well-sm no-shadow" style="text-transform:capitalize; word-break:break-all;">
            <span style="display: block;">Delivery Date: {{ $data['orders_data'][0]->delivery_date }}</span>
            @if(trim($data['orders_data'][0]->order_information) != '[]' and !empty($data['orders_data'][0]->order_information))
              {{ $data['orders_data'][0]->order_information }}
            @else
              ---
            @endif
          </p>

          <?php 
          $comment = '';
          $datas = $data['orders_data'];
          //echo "comment: ".$datas[0]->comments;exit;
          //$datas = array($datas);
          for($i=0; $i < count($datas); $i++){
            $DATA = $datas[$i];
            $comment .= "<p>".$DATA->comments."</p>";
            //echo "<pre>"; print_r($DATA); echo "</pre>".__FILE__.":".__LINE__;
          }

          echo "Order Comment:".$comment;
          ?>
          
        </div>  
      </div>
     
    </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>

