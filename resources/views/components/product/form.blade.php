@php
$route = $action === 'create' ? route('products.store') : route('products.update', ['product' => $product->id]);
$method = $action === 'create' ? 'POST' : 'PATCH';
@endphp

<form action="{{ $route }}" method="POST" data-table-selector="#data-table" data-form-action="{{ $method }}">
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

  <button type="submit" class="d-none"></button>
</form>
