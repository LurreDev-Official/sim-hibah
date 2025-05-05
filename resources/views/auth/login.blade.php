<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>SIM HIBAH UNHASY</title>
		<meta charset="utf-8" />
	 
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
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/sketchy-1/14.png)">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="#" class="mb-12">
						<img alt="Logo" src="{{ asset('image/logobr.png')}}" class="h-100px rounded" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <!--begin::Heading-->
							<div class="text-center mb-10">
								<!--begin::Title-->
								<h1 class="text-dark mb-3">Silahkan Login</h1>
								<!--end::Title-->
								<!--begin::Link-->
								<!--end::Link-->
							</div>
							<!--begin::Heading-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Label-->
								<label class="form-label fs-6 fw-bolder text-dark">Email</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" type="text" name="email" autocomplete="off" />
								<!--end::Input-->
								@error('email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Wrapper-->
								<div class="d-flex flex-stack mb-2">
									<!--begin::Label-->
									<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
									<!--end::Label-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Input-->
								<div class="input-group">
									<input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror" type="password" name="password" id="password" autocomplete="off" />
									<div class="input-group-append d-flex">
										<span class="input-group-text d-flex" onclick="togglePasswordVisibility()">
											<i class="fa fa-eye" id="togglePasswordIcon"></i>
										</span>
									</div>
								</div>
								@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>

							<script>
								function togglePasswordVisibility() {
									const passwordField = document.getElementById('password');
									const togglePasswordIcon = document.getElementById('togglePasswordIcon');
									const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
									passwordField.setAttribute('type', type);
									togglePasswordIcon.classList.toggle('fa-eye-slash');
								}
							</script>
							<!--end::Input group-->
							
							<!-- Error untuk login gagal -->
							@if($errors->has('email'))
							<div class="alert alert-danger">
								<strong>{{ $errors->first('email') }}</strong>
							</div>
							@endif

							<!--begin::Actions-->
							<div class="text-center">
								<!--begin::Submit button-->
								<button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
									<span class="indicator-label">Login</span>
									<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
								OR
								<a href="{{ route('google.redirect') }}" class="btn btn-danger w-100 mb-5">
									<i class="fab fa-google"></i> Login with Google
								</a>
								

							</div>
								<!--begin::Register Link-->
								<p class="text-center">
									Belum punya akun? <a href="{{ route('register') }}" class="link-primary fw-bolder">Daftar Sekarang</a>
								</p>
							<!--end::Actions-->
						</form>
						<!--end::Form-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Main-->
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js')}}"></script>
		<!--end::Global Javascript Bundle-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
