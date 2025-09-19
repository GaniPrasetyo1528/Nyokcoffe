{{-- Modal Edit --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg"> {{-- modal-lg biar lebih lebar --}}
    <form id="editForm" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            {{-- Kolom Kiri --}}
            <div class="col-md-6">
              <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="name" id="edit_name" class="form-control">
              </div>
              <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="price" id="edit_price" class="form-control">
              </div>
              <div class="mb-3 d-none" id="size_group">
                <label>Ukuran</label>
                <select name="size" id="edit_size" class="form-control">
                  <option value="">-- Pilih Ukuran --</option>
                  <option value="small">Small</option>
                  <option value="medium">Medium</option>
                  <option value="large">Large</option>
                </select>
              </div>
              <div class="mb-3 form-check">
  <input class="form-check-input" type="checkbox" name="is_available" id="edit_is_available" value="1">
  <label class="form-check-label" for="edit_is_available">
    Tersedia
  </label>
</div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_image_product" class="form-label">Upload Gambar</label>
                <input class="form-control" name="image" type="file" id="edit_image_product">

                {{-- Preview Gambar Lama --}}
                <div class="mt-2">
                  <img id="preview_edit_image" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
