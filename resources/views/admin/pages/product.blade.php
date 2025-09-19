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
                <i class="bi bi-plus-circle"></i> Tambah Customer
            </a>
        </div>

        <div class="card-body">
            {{-- Filter & Search --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ route('product.index', ['kategori' => 'makanan']) }}" 
                       class="btn btn-primary me-2">Makanan</a>
                    <a href="{{ route('product.index', ['kategori' => 'minuman']) }}" 
                       class="btn">Minuman</a>
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
                            <th>Price</th>
                            <th>image</th>
                            <th>Aksi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucwords($product->name) }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="80">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-warning btn-sm btn-edit" data-id="{{ $product->id }}">Edit</a>
                                <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </td>
                            <td>
                                @if($product->is_available)
                                    <span class="badge bg-success">Ready</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                        </tr>
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