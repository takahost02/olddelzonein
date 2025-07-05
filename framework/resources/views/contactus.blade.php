@extends("layouts.app")

@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.inquiries')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.inquiries')</h3>
      </div>
      <div class="card-body table-responsive">
        <table class="table" id="data_table11">
          <thead class="thead-inverse">
            <tr>
              <th><input type="checkbox" id="chk_all"></th>
              <th>@lang('fleet.user')</th>
              <th>@lang('fleet.email')</th>
              <th>@lang('fleet.message')</th>
              <th>@lang('fleet.delete')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($messages as $msg)
            <tr>
              <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $msg->id }}"></td>
              <td>{{ $msg->name }}</td>
              <td>{{ $msg->email }}</td>
              <td>{{ $msg->message }}</td>
              <td>
                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $msg->id }}" data-toggle="modal" data-target="#singleDeleteModal">
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>
                <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" title="@lang('fleet.delete')">
                  <i class="fa fa-trash"></i>
                </button>
              </th>
              <th>@lang('fleet.user')</th>
              <th>@lang('fleet.email')</th>
              <th>@lang('fleet.message')</th>
              <th>@lang('fleet.delete')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => 'admin/delete-inquiries', 'method' => 'POST', 'id' => 'form_delete']) !!}
        <div id="bulk_hidden"></div>
        <p>@lang('fleet.confirm_bulk_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- Single Delete Modal -->
<div id="singleDeleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => '', 'method' => 'POST', 'id' => 'single_delete_form']) !!}
        <p>@lang('fleet.confirm_bulk_delete')</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" type="submit">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
// Display notification based on session status
@if(Session::has('success'))
  new PNotify({
      title: 'Success!',
      text: '{{ Session::get('success') }}',
      type: 'success'
  });
@elseif(Session::has('error'))
  new PNotify({
      title: 'Failed!',
      text: '{{ Session::get('error') }}',
      type: 'error'
  });
@endif

$('#chk_all').on('click', function() {
  $('.checkbox').prop('checked', this.checked);
  $('#bulk_delete').prop('disabled', !this.checked);
  handleBulkHiddenInputs();
});

$('.checkbox').on('change', function() {
  var allChecked = $('.checkbox').length === $('.checkbox:checked').length;
  $('#chk_all').prop('checked', allChecked);
  $('#bulk_delete').prop('disabled', $('.checkbox:checked').length === 0);
  handleBulkHiddenInputs();
});

$('#bulk_delete').on('click', function() {
  var checkedBoxes = $("input[name='ids[]']:checked");
  if (checkedBoxes.length === 0) {
    new PNotify({
      title: 'Failed!',
      text: "@lang('fleet.delete_error')",
      type: 'error'
    });
    return;
  }
  // Clear existing hidden inputs before appending new ones
  $("#bulk_hidden").empty();
  checkedBoxes.each(function() {
    $("#bulk_hidden").append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
  });
});

// Single delete functionality
$('.delete-btn').on('click', function() {
  var id = $(this).data('id');
  var actionUrl = 'delete-inquiry/' + id;
  $('#single_delete_form').attr('action', actionUrl);
});

// Handle adding/removing hidden inputs for bulk delete
function handleBulkHiddenInputs() {
  $("#bulk_hidden").empty();
  $("input[name='ids[]']:checked").each(function() {
    $("#bulk_hidden").append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
  });
}
</script>
@endsection

