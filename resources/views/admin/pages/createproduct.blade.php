@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Tambah Produk</h2>

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row g-4">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select name="category_id" id="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="sizeWrapper" style="display: none;">
                                <label for="sizeMinuman" class="form-label">Size Minuman</label>
                                <select name="size" id="sizeMinuman"
                                        class="form-select @error('size') is-invalid @enderror">
                                    <option value="">-- Pilih Size Untuk Kategori Minuman --</option>
                                    <option value="small" {{ old('size') == 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ old('size') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ old('size') == 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="levelWrapper" style="display: none;">
                                <label for="levelMakanan" class="form-label">Level Makanan</label>
                                <input type="number" name="level" id="levelMakanan" min="0" max="10"
                                    class="form-control @error('level') is-invalid @enderror"
                                    value="{{ old('level') }}">
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" name="price" id="price" step="0.01"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Upload picture product</label>
                                <input class="form-control @error('image') is-invalid @enderror"
                                       name="image" type="file" id="formFileMultiple" multiple>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox"
                                       name="is_available" id="is_available"
                                       value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">
                                    Tersedia
                                </label>
                                @error('is_available')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('product.index') }}" class="btn btn-danger me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
