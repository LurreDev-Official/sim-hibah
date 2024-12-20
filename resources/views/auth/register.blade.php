<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>SIM HIBAH UNHASY</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico')}}" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Register -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/sketchy-1/14.png)">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="#" class="mb-12">
						<img alt="Logo" src="{{ asset('image/logo.png')}}" class="h-100px rounded" />
					</a>
					<!--end::Logo-->
					
					<!--begin::Register Card-->
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						<form method="POST" action="{{ route('register') }}">
							@csrf
							
							<!-- Nama Lengkap -->
							<div class="mb-3">
								<label for="name" class="form-label">Nama Lengkap</label>
								<input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="Nama Lengkap" required>
								@error('name')
									<span class="text-danger">{{ $message }}</span>
								@enderror
							</div>

							<!-- Email Instansi -->
							<div class="mb-3">
								<label for="email" class="form-label">Email Instansi</label>
								<input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email Instansi" required>
								@error('email')
									<span class="text-danger">{{ $message }}</span>
								@enderror
							</div>

							<!-- Password -->
							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" required>
								@error('password')
									<span class="text-danger">{{ $message }}</span>
								@enderror
							</div>

							<!-- Konfirmasi Password -->
							<div class="mb-3">
								<label for="password_confirmation" class="form-label">Konfirmasi Password</label>
								<input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Konfirmasi Password" required>
							</div>

							<!-- Role Selection -->
							<div class="mb-3">
								<label for="role" class="form-label">Sebagai</label>
								<select name="role" class="form-select form-select-lg @error('role') is-invalid @enderror" required>
									<option value="" disabled selected>Pilih peran</option>
									<option value="dosen">Dosen</option>
									<option value="reviewer">Reviewer</option>
								</select>
								@error('role')
									<span class="text-danger">{{ $message }}</span>
								@enderror
							</div>

							<!-- Submit Button -->
							<div class="d-grid gap-2 mt-3">
								<button type="submit" class="btn btn-lg btn-primary">DAFTAR</button>
							</div>
						</form>
						<!--end::Form-->
					</div>
					<!--end::Register Card-->

					<!-- Sudah punya akun -->
					<div class="text-center mt-4">
						Sudah punya akun? <a href="{{ route('login') }}" class="link-primary">Login</a>
					</div>
				</div>
				<!--end::Content-->
			</div>
			<!--end::Authentication - Register-->
		</div>
		<!--end::Main-->

		<!--begin::Javascript-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js')}}"></script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
