@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header with-button">
      {{-- Title --}}
      <h4><i class="fas fa-box mr-1"></i> {{ $title }}</h4>

      {{-- Actions --}}
      <div class="d-flex align-items-center">
        {{-- Export --}}
        <a href="{{ route('products.export') }}" class="btn btn-success btn-modal-trigger" data-modal=".export-modal">
          <i class="fas fa-download"></i>
          <span>Export</span>
        </a>

        {{-- Import --}}
        <a href="{{ route('products.import') }}" class="btn btn-danger btn-modal-trigger mx-2" data-modal=".import-modal">
          <i class="fas fa-upload"></i>
          <span>Import</span>
        </a>

        {{-- Create --}}
        <a href="{{ route('products.create') }}" class="btn btn-primary form-modal-trigger"
          data-modal-title="Create Product" data-modal-action="create">
          <i class="fas fa-plus mr-1"></i>
          <span>Create</span>
        </a>
      </div>
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
          data: 'actions',
          searchable: false,
          orderable: false,
        },
      ],
    })

  </script>
@endpush
