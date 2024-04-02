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


    <div class="container" >
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="dashboard-card" >
                    <div class="row">
                        <div class="col-md-4 dashboard-image">
                            <img src="{{ asset('assets/img/plan.jpg') }}" alt="Financial Reporting Image">
                        </div>
                        <div class="col-md-8">
                            <h2 >Financial Reporting and Planning</h2>
                            <p style="color: black; font-size: 16px;">Financial reporting and planning are essential processes for any business or organization to effectively manage its finances and make informed decisions.</p>
                            <p style="color: black; font-size: 16px;"><strong>Introduction to Financial Reporting:</strong> Financial reporting involves the preparation and presentation of financial information about a company's financial performance and position. It includes the creation of financial statements such as the income statement, balance sheet, and cash flow statement.</p>
                            <p style="color: black; font-size: 16px;"><strong>Introduction to Financial Planning:</strong> Financial planning is the process of setting goals, evaluating current financial resources, and developing strategies to achieve those goals. It encompasses various aspects such as budgeting, forecasting, investment planning, and risk management.</p>
                        </div>
                    </div>
                </div>
                <div class="dashboard-card" style="background-image: url('{{ asset('assets/img/bg.jpg') }}'); background-size: cover; background-color: rgba(255, 255, 255, 0.1);">
                    <h2>Cash Management</h2>
                    <p style="color: black; font-size: 16px;">Cash management involves the efficient management of cash inflows and outflows to optimize liquidity and ensure that the organization has sufficient funds to meet its obligations and pursue its objectives.</p>
<p style="color: black; font-size: 16px;"><strong>Introduction to Cash Management:</strong> Cash management is a critical component of financial management that focuses on maximizing the availability of cash while minimizing the cost of holding and accessing it. It includes activities such as cash forecasting, cash flow monitoring, liquidity management, and investment of excess cash.</p>

                    <div class="dashboard-image">
                        <img src="{{ asset('assets/img/finance_1dash.jpg') }}" alt="Cash Management Image">
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
