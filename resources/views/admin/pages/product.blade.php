@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Header --}}
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-gray">Daftar Customer</h6>
            <a href="{{ route('product.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Product
            </a>
        </div>

        <div class="card-body">
            {{-- Filter & Search --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ route('product.index', ['kategori' => 'makanan']) }}"
                    class="btn me-2 {{ request('kategori') == 'makanan' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Makanan
                    </a>

                    <a href="{{ route('product.index', ['kategori' => 'minuman']) }}"
                    class="btn {{ request('kategori') == 'minuman' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Minuman
                    </a>
                </div>

                <form action="{{ route('product.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Cari customer..." value="{{ request('search') }}">
                    <button class="btn btn-secondary" type="submit">Search</button>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Product</th>
                            <th>Kategory</th>
                            <th>Status</th>
                            <th>{{ request()->kategori == "minuman" ? 'Size' : 'Level' }}</th>
                            <th>Price</th>
                            <th>image</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            @if (request()->kategori == "minuman")
                                @php
                                    $iteration = 0;
                                @endphp
                                @foreach ($product->drinkdetail as $item)
                                    <tr>
                                        @if ($iteration == 0)
                                            <td rowspan="{{ count($product->drinkdetail) }}">{{ $products->firstItem() + $index }}</td>
                                            <td rowspan="{{ count($product->drinkdetail) }}">{{ $product->name }}</td>
                                            <td rowspan="{{ count($product->drinkdetail) }}">{{ ucfirst($product->category->name) }}</td>
                                        @endif
                                        @php
                                            $iteration++;
                                        @endphp
                                        <td>
                                        @if ($item->is_available == 1)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Unavailable</span>
                                        @endif
                                        </td>
                                        <td>
                                            {{ $item->size }}
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" width="50">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('product.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ route('product.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                           @php
                                    $iteration = 0;
                                @endphp
                                @foreach ($product->fooddetail as $item)
                                    <tr>
                                        @if ($iteration == 0)
                                            <td rowspan="{{ count($product->fooddetail) }}">{{ $products->firstItem() + $index }}</td>
                                            <td rowspan="{{ count($product->fooddetail) }}">{{ $product->name }}</td>
                                            <td rowspan="{{ count($product->fooddetail) }}">{{ ucfirst($product->category->name) }}</td>
                                        @endif
                                        @php
                                            $iteration++;
                                        @endphp
                                        <td>
                                        @if ($item->is_available == 1)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Unavailable</span>
                                        @endif
                                        </td>
                                        <td>
                                            {{ $item->level }}
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" width="50">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('product.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ route('product.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                </tbody>
                </table>
                {{-- Pagination links --}}
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection