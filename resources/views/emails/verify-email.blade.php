<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
      <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body p-5 text-center">
            
            {{-- Icon --}}
            <div class="mb-4">
              <i class="bi bi-envelope-check text-primary" style="font-size: 4rem;"></i>
            </div>

            {{-- Judul --}}
            <h3 class="fw-bold mb-3">Verifikasi Email</h3>

            {{-- Pesan --}}
            <p class="text-muted">
              Kami sudah mengirim link verifikasi ke email kamu. 
              Silakan cek inbox/spam, lalu klik link tersebut untuk mengaktifkan akun.
            </p>

            @if (session()->has('success') || session('status') == 'verification-link-sent')
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i>
                        {{-- Tampilkan pesan registrasi --}}
                        @if (session()->has('success'))
                            {{ session('success') }}
                        {{-- Tampilkan pesan verifikasi --}}
                        @elseif (session('status') == 'verification-link-sent')
                            Link verifikasi baru sudah dikirim ke email kamu.
                        @endif
                    </i>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif


            {{-- Resend verifikasi --}}
            <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
              @csrf
              <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-arrow-repeat"></i> Kirim Ulang Email Verifikasi
              </button>
            </form>

            {{-- Form ganti email --}}
            <div class="border-top pt-4 mt-3">
              <p class="text-muted mb-3">Salah mengetik email? Ubah email kamu di sini:</p>
              <form method="POST" action="{{ route('verification.update') }}">
                @csrf
                <div class="input-group">
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email baru" required>
                  <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-pencil-square"></i> Ubah
                  </button>
                  @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
