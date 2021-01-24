@php
$action = !isset($product) ? 'create' : 'update';
$route = $action === 'create' ? route('products.store') : route('products.update', ['product' => $product->id]);
$method = $action === 'create' ? 'POST' : 'PATCH';

$modalTitle = $action === 'create' ? 'Create Product' : 'Edit ' . $product->name;
$btnType = $action === 'create' ? 'btn-primary' : 'btn-warning';
$btnText = $action === 'create' ? 'Create' : 'Update'
@endphp

<div class="modal fade product-form" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $modalTitle }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- The Form --}}
        <form action="{{ $route }}" method="POST" class="need-ajax has-modal has-datatable" data-datatable="#data-table"
          enctype="multipart/form-data" @if ($action === 'update') data-stay-paging="1" @endif>
          @csrf @method($method)

          <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="text" class="form-control" name="name" placeholder="Name" @isset($product)
              value="{{ $product->name }}" @endisset required autofocus>
          </div>

          <div class="form-group">
            <label for="price">Price</label>
            <input id="price" type="number" class="form-control" name="price" placeholder="Price" @isset($product)
              value="{{ $product->price }}" @endisset required>
          </div>

          <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
              <option value="Y" @if (isset($product) && $product->status === 'Y') selected @endif>Available</option>
              <option value="N" @if (isset($product) && $product->status === 'N') selected @endif>Unavailable</option>
            </select>
          </div>

          @if (isset($product) && $product->image)
            <div class="my-1">
              <img src="/storage/{{ $product->image }}" alt="{{ $product->image }}" class="img-fluid">
            </div>
          @endif

          <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control-file">
          </div>

          <div class="d-flex justify-content-between">
            <button class="btn btn-light" data-dismiss="modal">Close</button>
            <button type="submit" class="btn {{ $btnType }}">{{ $btnText }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
