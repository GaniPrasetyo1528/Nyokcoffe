@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Tambah Produk</h2>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Kategori --}}
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-bold">Kategori</label>
                    <select name="category_id" id="category_id"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                data-name="{{ strtolower($cat->name) }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama Produk --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Variasi Produk --}}
                <h5 class="fw-bold mb-3">Variasi Produk</h5>
                <div id="variationWrapper" class="row g-3">
                    {{-- Default 1 variasi --}}
                    <div class="col-md-6 variation-item">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <span class="fw-semibold">Variasi</span>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-variation">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 level-col">
                                    <label class="form-label">Level</label>
                                    <input type="number" class="form-control input-level" min="0" max="10">
                                </div>
                                <div class="mb-3 size-col">
                                    <label class="form-label">Size</label>
                                    <select class="form-select input-size">
                                        <option value="">-- Pilih Size --</option>
                                        <option value="small">Small</option>
                                        <option value="medium">Medium</option>
                                        <option value="large">Large</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga</label>
                                    <input type="number" class="form-control input-price">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gambar</label>
                                    <input type="file" class="form-control input-image">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input input-available" checked>
                                    <label class="form-check-label">Tersedia</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol tambah variasi --}}
                <div class="mt-3">
                    <button type="button" id="addVariation" class="btn btn-success">
                        <i class="bi bi-plus-lg"></i> Tambah Variasi
                    </button>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('product.index') }}" class="btn btn-danger">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Script Dinamis Variasi & Kategori --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const wrapper = document.getElementById('variationWrapper');
    const addBtn = document.getElementById('addVariation');

    function reindexVariations() {
        const items = wrapper.querySelectorAll('.variation-item');
        items.forEach((item, idx) => {
            item.querySelector('.input-level')?.setAttribute('name', `variations[${idx}][level]`);
            item.querySelector('.input-size')?.setAttribute('name', `variations[${idx}][size]`);
            item.querySelector('.input-price')?.setAttribute('name', `variations[${idx}][price]`);
            item.querySelector('.input-image')?.setAttribute('name', `variations[${idx}][image]`);
            item.querySelector('.input-available')?.setAttribute('name', `variations[${idx}][is_available]`);
        });
    }

    function updateFieldsByCategory() {
        const selectedText = categorySelect.options[categorySelect.selectedIndex]?.text?.toLowerCase() ?? '';
        wrapper.querySelectorAll('.variation-item').forEach(item => {
            const levelCol = item.querySelector('.level-col');
            const sizeCol  = item.querySelector('.size-col');
            const inpLevel = item.querySelector('.input-level');
            const inpSize  = item.querySelector('.input-size');

            if (selectedText === 'makanan') {
                levelCol.classList.remove('d-none');
                sizeCol.classList.add('d-none');
                inpLevel.required = true;
                inpSize.required = false;
                inpSize.value = '';
            } else if (selectedText === 'minuman') {
                sizeCol.classList.remove('d-none');
                levelCol.classList.add('d-none');
                inpSize.required = true;
                inpLevel.required = false;
                inpLevel.value = '';
            } else {
                levelCol.classList.add('d-none');
                sizeCol.classList.add('d-none');
                inpLevel.required = false;
                inpSize.required = false;
            }
        });
    }

    addBtn.addEventListener('click', function () {
        const newItem = document.createElement('div');
        newItem.className = 'col-md-6 variation-item';
        newItem.innerHTML = `
            <div class="card border shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <span class="fw-semibold">Variasi</span>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-variation">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-3 level-col">
                        <label class="form-label">Level</label>
                        <input type="number" class="form-control input-level" min="0" max="10">
                    </div>
                    <div class="mb-3 size-col">
                        <label class="form-label">Size</label>
                        <select class="form-select input-size">
                            <option value="">-- Pilih Size --</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control input-price">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" class="form-control input-image">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input input-available" checked>
                        <label class="form-check-label">Tersedia</label>
                    </div>
                </div>
            </div>
        `;
        wrapper.appendChild(newItem);
        reindexVariations();
        updateFieldsByCategory();
    });

    wrapper.addEventListener('click', function(e){
        if (e.target.closest('.remove-variation')) {
            e.target.closest('.variation-item').remove();
            reindexVariations();
        }
    });

    categorySelect.addEventListener('change', updateFieldsByCategory);

    reindexVariations();
    updateFieldsByCategory();
});
</script>
@endsection
