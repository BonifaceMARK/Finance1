@extends('layout.title')

@section('title', 'Dashboard')
@include('layout.title')

@include('layout.header')
<body>

  <!-- ======= Sidebar ======= -->
@include('layout.sidebar')

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav class="custom-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
    </div><!-- End Page Title -->

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="image-container">
                                    <img src="{{ asset('assets/img/finance_1dash.jpg') }}" alt="Financial Planning Image" class="img-fluid rounded-lg">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
 @include('layout.footer')


</body>

</html>
