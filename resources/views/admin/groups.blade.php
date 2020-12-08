@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Groups <a data-toggle="modal" data-target="#editHolidayModal" data-placement="bottom" title="Edit" href="#" class="bg-light-blue btn btn-primary group_addnew">Add New</a> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/orders')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active"> Groups </li>
    </ol>
  </section>
  
  <!--  content -->
  <section class="content"> 
    <!-- Info boxes --> 
    
    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">          
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
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped" style="width: 100%; max-width: 100%;">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.ID') }}</th>
                      <th>Name</th> 
                      <th>{{ trans('labels.Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($result['holiday'])>0)
                        <?php 
                        //echo "<pre>"; print_r($result['holiday']); echo "</pre>".__FILE__.":".__LINE__;
                        ?>
                        @foreach($result['holiday'] as $holiday)
                        <tr>
                            <td>{{ $holiday->id }}</td>
                            <td>{{ $holiday->name }}</td>
                            <td>
                                <a data-toggle="modal" data-target="#editHolidayModal" data-placement="bottom" data-id="{{ $holiday->id }}" data-name="{{ $holiday->name }}" title="Edit" href="#" class="badge bg-light-blue btn btn-primary holiday_edit">Edit</a> 
                                <a data-toggle="modal" data-target="#deleteholidayModal" data-placement="bottom" data-id="{{ $holiday->id }}" title="{{ trans('labels.DeleteDevice') }}" class="badge bg-red btn btn-danger delete_holiday">{{ trans('labels.Delete') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">{{ trans('labels.NoRecordFound') }}</td>
                        </tr>
                    @endif
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                  {{$result['holiday']->links('vendor.pagination.default')}}
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
    <!-- editHoliday -->
    <div class="modal fade" id="editHolidayModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deletedeviceModalLabel">Group Detail</h4>
            </div>
            {!! Form::open(array('url' =>'admin/updategroup', 'name'=>'editdevice', 'id'=>'editdevice', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                {!! Form::hidden('action',  'POST', array('class'=>'form-control')) !!}
                {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'edit_holiday_id')) !!}
                <div class="modal-body">            
                    <div class="form-group">
                          <label for="name" class="col-sm-2 col-md-3 control-label">Name</label>
                          <div class="col-sm-10 col-md-4">
                               <input type="text" name="name" value="" id="holiday_name" class="form-control">
                          </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            {!! Form::close() !!}
        </div>
        </div>
    </div>

    <!-- deletedeviceModal -->
    <div class="modal fade" id="deleteholidayModal" tabindex="-1" role="dialog" aria-labelledby="deletedeviceModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Group</h4>
            </div>
            {!! Form::open(array('url' =>'admin/deletegroup', 'name'=>'deleteholiday', 'id'=>'deleteholiday', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
                {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'holiday_id')) !!}
                <div class="modal-body">            
                    <p>Are you sure you want to delete this group?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>
                    <button type="submit" class="btn btn-primary" id="deletedevice">{{ trans('labels.Delete') }}Delete</  button>
                </div>
            {!! Form::close() !!}
        </div>
        </div>
    </div>
    
    <!--  row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 