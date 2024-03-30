@extends('layout.title')

@section('title', 'Register')
@include('layout.title')

<body>

<main>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="/" class="logo d-flex align-items-center w-auto">
                                <img src="assets/img/fmslogo.png" alt="">
                                <span class="d-none d-lg-block">Financial Guardians</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                    <p class="text-center small">Enter your personal details to create account</p>
                                </div>
                                <form action="{{route('register')}}" method="post" class="row g-3 needs-validation" novalidate id="registerform">
                                    @if(Session::has('success'))
                                    <div class="alert alert-success">{{Session::get('success')}}</div>
                                    @endif
                                    @if(Session::has('fail'))
                                    <div class="alert alert-danger">{{Session::get('fail')}}</div>
                                    @endif
                                    @csrf
                                    <div class="col-12">
                                        <label for="yourName" class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="yourName" required>
                                        <div class="invalid-feedback">Please, enter your name!</div>
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                    <div class="col-12">
                                        <label for="yourEmail" class="form-label">Your Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="yourEmail" required>
                                        <div class="text-danger">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourDepartment" class="form-label">Department</label>
                                        <div class="input-group has-validation">
                                            <select name="department" class="form-select" id="yourDepartment" required>
                                                <option value="" selected disabled>Select Department</option>
                                                <option value="Administration">Administration</option>
                                                <option value="Logistics">Logistics</option>
                                                <option value="Human Resource">Human Resource</option>
                                                <option value="Hotel & Restaurant">Hotel & Restaurant</option>
                                                <option value="E-Commerce">E-Commerce</option>
                                                <option value="Local Government Unit">Local Government Unit</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a department.</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" id="yourPassword" required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="bi bi-eye"></i></button>

                                        </div>
                                        <div class="invalid-feedback">Please enter your password!</div>
                                        <span class="text-danger">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Register</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="text-center small mb-0">Already have an account?<a href="{{route('loadlogin')}}">Log in</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main><!-- End #main -->



<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('yourPassword');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    });
</script>

</body>

</html>
