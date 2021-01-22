@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header with-button">
      <h4><i class="fas fa-box mr-1"></i> {{ $title }}</h4>
      <a href="{{ route('products.create') }}" class="btn btn-primary form-modal-trigger"
        data-modal-title="Create Product" data-modal-action="create">
        <i class="fas fa-plus mr-1"></i>
        <span>Create</span>
      </a>
    </div>

    <div class="card-body">
      <table id="data-table" class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>status</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('script')
  <script>
    $('table#data-table').DataTable({
      serverSide: true,
      responsive: true,
      ajax: "{{ route('products.index') }}",
      columns: [{
          data: 'id'
        },
        {
          data: 'image',
          render: function(data) {
            return !data ? 'null' : `<img class="img-fluid" src="/storage/${data}">`
          }
        },
        {
          data: 'name'
        },
        {
          data: 'price'
        },
        {
          data: 'status',
          render: function(data) {
            const icon = data.toLowerCase() === 'y' ? 'fa-check' : 'fa-times'
            const textType = data.toLowerCase() === 'y' ? 'text-success' : 'text-danger'

            return `<i class="fas ${icon} ${textType}"></i>`
          }
        },
        {
          data: 'actions'
        },
      ]
    })

  </script>
@endpush
