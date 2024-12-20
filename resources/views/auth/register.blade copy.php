<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register Page</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo d-flex align-items-center justify-content-center">
                  <img src="{{ asset('image/Logo-unhasy.webp') }}" alt="logo" style="width: 50px; height: auto; margin-right: 10px;">
                  <h1 style="font-size: 1.5rem;">SISTEM HIBAH UNHASY</h1>
                </div>
                <h4>Welcome! Register here</h4>
                <form method="POST" action="{{ route('register') }}" class="pt-3">
                  @csrf
                  <!-- Nama Lengkap -->
                  <div class="form-group">
                    <input type="text" name="name" class="form-control form-control-lg" id="exampleInputName" placeholder="Nama Lengkap" required>
                  </div>
                  <!-- Email Instansi -->
                  <div class="form-group">
                    <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail" placeholder="Email Instansi" required>
                  </div>
                  <!-- Password -->
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword" placeholder="Password" required>
                  </div>
                  <!-- Konfirmasi Password -->
                  <div class="form-group">
                    <input type="password" name="password_confirmation" class="form-control form-control-lg" id="exampleInputConfirmPassword" placeholder="Konfirmasi Password" required>
                  </div>
                  <!-- Sebagai (Dosen / Reviewer) -->
                  <div class="form-group">
                    <select name="role" class="form-select form-select-lg" id="exampleFormControlSelectRole" required>
                      <option value="" disabled selected>Sebagai</option>
                      <option value="dosen">Dosen</option>
                      <option value="reviewer">Reviewer</option>
                    </select>
                  </div>
                  <!-- Button Daftar -->
                  <div class="mt-3 d-grid gap-2">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">DAFTAR</button>
                  </div>
                  <!-- Sudah punya akun -->
                  <div class="text-center mt-4 font-weight-light">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-primary">Login</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->
  </body>
</html>
