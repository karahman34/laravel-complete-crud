<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard') }}">{{ env('APP_NAME') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('dashboard') }}">{{ env('APP_NAME') }}</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>

      {{-- Dropdown Example --}}
      {{-- <li class="nav-item dropdown active">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
        <ul class="dropdown-menu">
          <li class="active"><a class="nav-link" href="index-0.html">General Dashboard</a></li>
          <li><a class="nav-link" href="index.html">Ecommerce Dashboard</a></li>
        </ul>
      </li> --}}

      {{-- Dashboard --}}
      <li><a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i>
          <span>Dashboard</span></a>
      </li>

      {{-- Products --}}
      <li>
        <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-box"></i>
          <span>Products</span></a>
      </li>
    </ul>
  </aside>
</div>
