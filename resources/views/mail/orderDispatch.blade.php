<div style="max-width: 600px;  margin: 0 auto; background: #fff; padding: 10px; border: 1px solid #ddd;">
<div style="background: #fff; padding: 9px 10px;">
	<div class="top-mail" style="">
		<center><img src="{{ URL::to('').'/resources/assets/images/site_images/smaller_logo_for_mail.png' }}" ></center>
		<h2 style="color: #d3742a; font-size: 20px; font-weight: 300; margin-top: 50px">A new order has been placed</h2>
	</div>
</div>
<div style="background: #fff; padding:10px;">
	<p style="clear:both;">Now you can dispatch the order. The order details are shown below for your reference:</p>
  <div style="width: 100%; display: block">
    <h2 style="font-size: 20px; border-bottom: 1px solid #eee;padding-bottom: 20px; ">
	<span style="">{{ trans('labels.OrderID') }}# {{ $ordersData['orders_data'][0]->orders_id }}</span>
	<span style="float:right">	Purchase Order# {{ $ordersData['orders_data'][0]->order_no }}</span>
 </h2>
  </div> 
  
  
  
  <!-- Table row -->
  <table class="table table-striped" style="border-collapse: collapse; width: 100%;background-color: transparent;margin: 15px 0 15px;">
    <thead>
      <tr>
        <th align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">{{ trans('labels.ProductName') }}</th>
        <th align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">SKU</th>
        <th align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">{{ trans('labels.Qty') }}</th>
        <th align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">Price</th>
        <th align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">Total</th>
      </tr>
    </thead>
    <tbody style="text-transform: capitalize;font-size: 12px;">
     <?php
	 $eachProductTotal=0;	 
	 $allProductsTotal=0;	 
	 foreach($ordersData['orders_data'][0]->data as $key=>$products) { 
	 $eachProductTotal =  $products->products_quantity*$products->products_price ; 
	 $allProductsTotal += $products->products_quantity*$products->products_price;
	 ?>
      <tr if($key%2==0){ style="background-color: #fff;" } >
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;">{{  $products->products_name }}</td>
        <td style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;">{{  $products->sku }}</td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">{{  $products->products_quantity }}</td>
        <td align="right" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">${{  number_format($products->products_price,2) }}</td>
        <td align="right" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">${{  number_format($eachProductTotal,2) }}</td>
      </tr>
     <?php } ?>
      </tr>
	 <tr>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;"></td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;"></td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;"></td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">Sub Total</td>
        <td align="right" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">${{ number_format($allProductsTotal,2) }}</td>
	</tr>
	<?php 
	$GST=$allProductsTotal*($ordersData['tax_rate_setting']/100);
	$TOTAL=$allProductsTotal+$GST;
	?>
	<tr>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;"></td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;"></td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;"></td>	
		<td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">GST</td>
        <td align="right" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">${{ number_format($GST,2) }}</td>
	</tr>	
	<tr>
		<td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;"></td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px; font-size:14px;"></td>
        <td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;"></td>
		<td align="left" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">Total</td>
        <td align="right" style="border: 1px solid #f4f4f4; padding:12px 8px;font-size:14px;">${{ number_format($TOTAL,2) }}</td>
     </tr>
      
    </tbody>
  </table>
  
  <!-- /.row -->
  <?php 
  $DeliveryDate= str_replace("-","/",$ordersData['orders_data'][0]->delivery_date); 
  ?>
	<!-- <small style="font-size: 14px;float: left;padding-right: 12px;margin-top: 6px;">Delivery Date: <?php // echo $DeliveryDate;?></small>  -->
  <!-- info row -->
  <div style="display: block; width: 100%;padding: 0 0 20px; clear: both;">
    <div style="display: none; width:100%"> <strong style=" font-size:18px;   color: #557da1;">{{ trans('labels.CustomerInfo') }}:</strong>
      <address style="font-style: normal;">
      <strong>Name: </strong><span style="text-transform: capitalize;">{{ $ordersData['orders_data'][0]->customers_name }}</span><br>
     <strong>Address: </strong> {{ $ordersData['orders_data'][0]->customers_street_address }} <br>
        {{ $ordersData['orders_data'][0]->customers_city }} {{ $ordersData['orders_data'][0]->customers_state }} {{ $ordersData['orders_data'][0]->customers_postcode }} {{ $ordersData['orders_data'][0]->customers_country }}<br>
        <strong>{{ trans('labels.Phone') }}:</strong> {{ $ordersData['orders_data'][0]->customers_telephone }}<br>
        <strong>{{ trans('labels.Email') }}:</strong> {{ $ordersData['orders_data'][0]->email }}
      </address>
    </div>
    <div style="display: inline-block; width:48%"> <strong style="   font-size:18px;   color: #557da1;">{{ trans('labels.ShippingInfo') }}:</strong>
      <address style="font-style: normal;">
      <span style="text-transform: capitalize;">{{ $ordersData['orders_data'][0]->delivery_company }}</span><br>
      {{ $ordersData['orders_data'][0]->customers_name }} <br>
      {{ $ordersData['orders_data'][0]->delivery_street_address }} <br>
      {{ $ordersData['orders_data'][0]->delivery_city != '' ? $ordersData['orders_data'][0]->delivery_city.' ' : '' }}
      {{ $ordersData['orders_data'][0]->delivery_suburb != '' ? $ordersData['orders_data'][0]->delivery_suburb.' ' : '' }}
      {{ $ordersData['orders_data'][0]->delivery_state }} {{ $ordersData['orders_data'][0]->delivery_postcode }}
      <br>
      Delivery phone: {{ $ordersData['orders_data'][0]->delivery_phone }} <br>
      </address>
      <p>Order Comments: {{ $ordersData['orders_status_history'][0]->comments }}</p>
    </div>
  </div>
  <!-- /.row --> 
  <!-- /.content --> 

</div>
</div>