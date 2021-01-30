@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header with-button">
      {{-- Title --}}
      <h4><i class="fas fa-box mr-1"></i> {{ $title }}</h4>

      {{-- Actions --}}
      <div class="d-flex align-items-center">
        {{-- Export --}}
        @include('components.button.export-btn', ['action' => route('products.export')])

        {{-- Import --}}
        @include('components.button.import-btn', ['action' => route('products.import')])

        {{-- Create --}}
        @include('components.button.create-btn', [
        'action' => route('products.create'),
        'modal' => '.product-form'
        ])
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
          searchable: false,
          orderable: false,
          render: function(data) {
            if (!data) {
              return '<span class="text-muted font-italic">null</span>'
            }

            return `<img class="img-fluid" src="${data}" alt="product-image" />`
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
