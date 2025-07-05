<div class="btn-group">

    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">

      <span class="fa fa-gear"></span>

      <span class="sr-only">Toggle Dropdown</span>

    </button>

    <div class="dropdown-menu custom" role="menu">

      <a class="dropdown-item" class="mybtn changepass" data-id="{{$row->id}}" data-toggle="modal" data-target="#changepass" title="@lang('fleet.change_password')"><i class="fa fa-key" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.change_password')</a>

      @can('Drivers edit')<a class="dropdown-item" href="{{ url("admin/vowner/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>@endcan

      @can('Drivers delete')<a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>@endcan



      @if(Hyvikk::get('driver_doc_verification') == 1)

      @if($row->getMeta('is_verified') != '' && $row->getMeta('is_verified') == 0)

      <a class="dropdown-item" href="{{ url("admin/vowner/verify-vowner/".$row->id)}}" target="_black"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span>Verify Vehicle Owner Check Document</a>

      @elseif($row->getMeta('is_verified') != '' && $row->getMeta('is_verified') == 1)

      <a class="dropdown-item" href="{{ url("admin/vowner/verify-vowner/".$row->id)}}" target="_black"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span>Verify Vehicle Owner Check Document</a>

      @elseif($row->getMeta('is_verified') != '' && $row->getMeta('is_verified') == 2)

      <a class="dropdown-item" href="{{ url("admin/vowner/verify-vowner/".$row->id)}}" target="_black"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span>Verify Vehicle Owner Check Document</a>

      @else

      <a class="dropdown-item" href="{{ url("admin/vowner/verify-vowner/".$row->id)}}" target="_black"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span>Verify Vehicle Owner Check Document</a>

      @endif

      
      @endif
  

      @if($row->getMeta('is_active'))

      <a class="dropdown-item" href="{{ url("admin/vowner/disable/".$row->id)}}" class="mybtn" data-toggle="tooltip"  title="@lang('fleet.disable_vowner')"><span class="fa fa-times" aria-hidden="true" style="color: #5cb85c;"></span> @lang('fleet.disable_vowner')</a>

      @else

      <a class="dropdown-item" href="{{ url("admin/vowner/enable/".$row->id)}}" class="mybtn" data-toggle="tooltip"  title="@lang('fleet.enable_vowner')"><span class="fa fa-check" aria-hidden="true" style="color: #5cb85c;"></span> @lang('fleet.enable_vowner')</a>

      @endif

    </div>

  </div>

  {!! Form::open(['url' => 'admin/vowner/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}

  {!! Form::hidden("id",$row->id) !!}

  {!! Form::close() !!}