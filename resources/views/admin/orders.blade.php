@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.Orders') }} <small>{{ trans('labels.ListingAllOrders') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.Orders') }}</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- Info boxes --> 
    
    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ trans('labels.ListingAllOrders') }} </h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                 @if (count($errors) > 0)
                      @if($errors->any())
                        <div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          {{$errors->first()}}
                        </div>
                      @endif
                  @endif
                    @if(session()->has('error'))
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="alert alert-warning alert-dismissible">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <i class="icon fa fa-warning"></i> {{ session()->get('error') }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(session()->has('success'))
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="alert alert-success alert-dismissible">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <i class="icon fa fa-check"></i> {{ session()->get('success') }}
                            </div>
                        </div>
                    </div>
                    @endif
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-9">
                        <ul class="action_status">
                            <li class="all first-child">
                                <?php if(empty($_GET['action']) and empty($_GET['status'])) echo "<b>" ?>
                                    <a href="{{ URL::to('admin/orders')}}">All (<?php echo $listingOrders['total_order']['total_orders'] ?>)</a>
                                <?php if(empty($_GET['action']) and empty($_GET['status'])) echo "</b>" ?>
                            </li>
                            <li class="trash">
                                <?php if(isset($_GET['action']) and $_GET['action'] == 'trash') echo "<b>" ?>
                                    <a href="{{ URL::to('admin/orders?action=trash')}}">Trash (<?php echo $listingOrders['total_order']['total_trash'] ?>)</a>
                                <?php if(isset($_GET['action']) and $_GET['action'] == 'trash') echo "</b>" ?>
                            </li>
                            <li class="processing">
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'pending') echo "<b>" ?>
                                    <a href="{{ URL::to('admin/orders?status=pending')}}">Processing (<?php echo $listingOrders['total_order']['total_pending'] ?>)</a>
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'pending') echo "</b>" ?>
                            </li>
                            <li class="completed">
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'completed') echo "<b>" ?>
                                <a href="{{ URL::to('admin/orders?status=completed')}}">Completed (<?php echo $listingOrders['total_order']['total_completed'] ?>)</a>
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'completed') echo "</b>" ?>
                            </li>
                            <li class="cancelled">
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'cancelled') echo "<b>" ?>
                                <a href="{{ URL::to('admin/orders?status=cancelled')}}">Cancelled (<?php echo $listingOrders['total_order']['total_cancelled'] ?>)</a>
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'cancelled') echo "</b>" ?>
                            </li>
                            <li class="return">
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'returned') echo "<b>" ?>
                                <a href="{{ URL::to('admin/orders?status=returned')}}">Return (<?php echo $listingOrders['total_order']['total_return'] ?>)</a>
                                <?php if(isset($_GET['status']) and $_GET['status'] == 'returned') echo "</b>" ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-3">  
                        <form action="{{URL::to('/admin/orders')}}" method="GET" id="search_order">
                            <div class="input-group input-group-sm">
                                <input type="search" id="order_search" name="order_search" value="" class="form-control input-sm" placeholder="Search Order ID">
                                <span class="input-group-btn">
                                  <button type="submit" class="btn btn-info btn-flat">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <form action="{{URL::to('/admin/bulkupdateorders')}}" method="POST" id="orders_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="toolbar row">
                        <div class="bulk_action col-xs-2">
                            <select name="bulk_action" id="bulk-action-selector-top" class="form-control  input-sm">
                                <option value="">Bulk Actions</option>
                                <?php 
                                if(isset($_GET['action']) and ($_GET['action'] == 'trash')){
                                ?>
                                <option value="restore">Restore</option>
                                <option value="delete">Delete</option>
                                <?php }else{ ?>
                                <option value="trash">Move to Trash</option>
                                <?php } ?>
                                <option value="pending">Mark pending</option>
                                <option value="cancelled">Mark cancel</option>
                                <option value="completed">Mark complete</option>
                                <option value="returned">Mark return</option>
                            </select>
                        </div>
                        <input type="submit" id="doaction" class="btn btn-sm col-xs-1" value="Apply">
                    </div>
                    <table id="orderlist" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th width="20"><input type="checkbox" value="" id="order_selectall" /></th>						  
                         				  
						  <th>Order Number</th>
						  <th>Purchase Order No.</th>
                          <th>Company</th>
                          {{-- <th>{{ trans('labels.OrderTotal') }}</th> --}}
                          <th>Delivery Date</th>
                          <th>Delivery Address</th>
                          <th>{{ trans('labels.Status') }} </th>
                          <th>{{ trans('labels.DatePurchased') }}</th>
                          <th>{{ trans('labels.Action') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                      @if(count($listingOrders['orders'])>0)
                        @foreach ($listingOrders['orders'] as $key=>$orderData)						 
                            <tr>
                                <td><input type="checkbox" name="orders[]" value="{{ $orderData->orders_id }}"></td>		
								<td>{{ $orderData->orders_id }}</td>
								<td>{{ $orderData->order_no }}</td>
                                <td>{{ $orderData->customers_company }}</td>
                                {{-- <td>{{ $listingOrders['currency'][19]->value }}{{ $orderData->order_price }}</td> --}}
                                <td>{{ $orderData->delivery_date }} </td>
                                <td>{{ $orderData->delivery_name }} {{ $orderData->delivery_company }} {{ $orderData->delivery_street_address }} {{ $orderData->delivery_suburb }} {{ $orderData->delivery_phone }}</td>
                                <td>
                                  @if($orderData->orders_status_id==1)
                                    <span class="label label-warning">
                                    @elseif($orderData->orders_status_id==2)
                                        <span class="label label-success">
                                    @elseif($orderData->orders_status_id==3)
                                         <span class="label label-danger">
                                    @else
                                         <span class="label label-primary">
                                    @endif
                                    {{ $orderData->orders_status }}
                                         </span>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($orderData->date_purchased)) }} </td>
                                <td>
                                <a data-toggle="tooltip" data-placement="bottom" title="View Order" href="vieworder/{{ $orderData->orders_id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                
                                 <a data-toggle="tooltip" data-placement="bottom" title="Delete Order" id="deleteOrdersId" orders_id ="{{ $orderData->orders_id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                
                                </td>
                                
                            </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="7"><strong>{{ trans('labels.NoRecordFound') }}</strong></td>
                        </tr>
                      @endif
                      </tbody>
                    </table>
                </form>
                <div class="col-xs-12 text-right">
                  {{$listingOrders['orders']->appends(request()->query())->links('vendor.pagination.default')}}
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
    
      <!-- deleteModal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="deleteModalLabel">{{ trans('labels.DeleteOrder') }}</h4>
      </div>
      {!! Form::open(array('url' =>'admin/deleteOrder', 'name'=>'deleteOrder', 'id'=>'deleteOrder', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
          {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
          {!! Form::hidden('orders_id',  '', array('class'=>'form-control', 'id'=>'orders_id')) !!}
      <div class="modal-body">            
        <p>{{ trans('labels.DeleteOrderText') }}</p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
      <button type="submit" class="btn btn-primary" id="deleteOrder">{{ trans('labels.Delete') }}</button>
      </div>
      {!! Form::close() !!}
    </div>
    </div>
  </div>
    
    <!-- Main row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 