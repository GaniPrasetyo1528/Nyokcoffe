<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #fff, #fff);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .register-card {
      background: #fff;
      border-radius: 15px;
      padding: 2rem;
      max-width: 700px; /* Lebar diperbesar */
      width: 100%;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .register-card h3 {
      font-weight: 700;
      color: #333;
    }
    .form-control {
      padding: 0.75rem 1rem; /* Input lebih besar */
      font-size: 1.05rem;
    }
    .form-control:focus {
      border-color: #6c63ff;
      box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
    }
    .btn-custom {
      background: #6c63ff;
      border: none;
      transition: 0.3s;
      font-size: 1.1rem;
      padding: 0.75rem;
    }
    .btn-custom:hover {
      background: #5848d9;
    }
    .toggle-password {
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="register-card">
    <h3 class="text-center mb-4">Create an Account</h3>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="row g-4"> <!-- g-4 = jarak antar kolom -->
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required value="{{ old('email') }}">
                @error('email')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Enter your username" required value="{{ old('username') }}">
                @error('username')
                  <smal class="text-danger">{{ $message }}</smal>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="nowhatsapp" class="form-label">No WhatsApp</label>
                <input type="text" name="phone" class="form-control" id="nowhatsapp" placeholder="Enter your number" required value="{{ old('phone') }}">
                @error('phone')
                  <smal class="text-danger">{{ $message }}</smal>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" id="address" placeholder="Enter your address" required value="{{ old('address') }}">
                @error('address')
                  <smal class="text-danger">{{ $message }}</smal>
                @enderror
            </div>

            {{-- Geolocation --}}
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            {{-- Endgeolocation --}}

            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                    <span class="input-group-text toggle-password" data-target="#password"><i class="bi bi-eye-slash"></i></span>
                </div>
                @error('password')
                  <smal class="text-danger">{{ $message }}</smal>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" class="form-control" id="confirmPassword" placeholder="Confirm password" required>
                    <span class="input-group-text toggle-password" data-target="#confirmPassword"><i class="bi bi-eye-slash"></i></span>
                </div>
                @error('password_confirmation')
                  <smal class="text-danger">{{ $message }}</smal>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-custom text-white w-100 mt-4">Register</button>
        <p class="text-center mt-3">Already have an account? <a href="/login" class="text-decoration-none">Login</a></p>
    </form>
  </div>

  <script>
    document.querySelectorAll('.toggle-password').forEach(function (icon) {
      icon.addEventListener('click', function () {
        const target = document.querySelector(this.getAttribute('data-target'));
        const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
        target.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
      });
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            },
            function (error) {
                console.warn("Geolocation error:", error.message);
            }
        );
    } else {
        console.warn("Geolocation is not supported by this browser.");
    }
  </script>

</body>
</html>
