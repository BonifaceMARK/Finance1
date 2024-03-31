@extends('layout.title')

@section('title', 'Financial Reporting')
@include('layout.title')

@include('layout.header')
<body>

  <!-- ======= Sidebar ======= -->
@include('layout.sidebar')

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Financial Reporting</h1>
      <nav class="custom-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Financial Reporting</li>
        </ol>
    </nav>
    </div><!-- End Page Title -->

    <div class="col-xxl-4 col-md-6">
        <div class="card info-card revenue-card">

            <div class="card-body">
                <h5 class="card-title">Cash Inflow / Revenue <span>| This Month</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                        @if(!empty($paymentData))
                            <h6>${{ $paymentData[0]['transactionAmount'] }}</h6>
                        @else
                            <h6>No income</h6>
                        @endif
                        @if(isset($previousRevenue))
                            <span class="text-success small pt-1 fw-bold">{{ number_format($percentageIncrease, 2) }}%</span>
                            @if($percentageIncrease > 0)
                                <span class="text-muted small pt-2 ps-1">increase</span>
                            @else
                                <span class="text-error small pt-2 ps-1">decrease</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Revenue Card -->

    <div class="col-xxl-4 col-md-6">
        <div class="card info-card expenses-card">

            <div class="card-body">
                <h5 class="card-title">Expenses <span>| Today</span></h5>

                @if (!is_null($expenses) && count($expenses) > 0)
    @foreach($expenses as $expense)
        <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="ps-3">
                <h6>{{ $expense['amount'] }}</h6>
                <span class="text-success small pt-1 fw-bold">{{ number_format($expense['percentageIncrease'] ?? 0, 2) }}%</span>
                @if(isset($expense['percentageIncrease']))
                    @if($expense['percentageIncrease'] > 0)
                        <span class="text-muted small pt-2 ps-1">increase</span>
                    @else
                        <span class="text-muted small pt-2 ps-1">decrease</span>
                    @endif
                @else
                    <span class="text-muted small pt-2 ps-1">No change</span>
                @endif
            </div>
        </div>
    @endforeach
@else
    <p>No expenses found.</p>
@endif

            </div>

        </div>
    </div><!-- End Expenses Card -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">Financial Report: Cost Allocation</h5>
                    </div>
    
                    <div class="card-body">
                        @if(!is_null($costs) && count($costs) > 0)

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-secondary text-white">
                                            <th>ID</th>
                                            <th>Cost Center</th>
                                            <th>Cost Category</th>
                                            <th>Allocation Method</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Created At</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($costs as $cost)
                                            <tr>
                                                <td>{{ $cost['id'] }}</td>
                                                <td>{{ $cost['cost_center'] }}</td>
                                                <td>{{ $cost['cost_category'] }}</td>
                                                <td>{{ $cost['allocation_method'] }}</td>
                                                <td>${{ number_format($cost['amount'], 2) }}</td>
                                                <td>{{ $cost['description'] ?? 'N/A' }}</td>
                                                <td>{{ $cost['created_at'] }}</td>
                                               
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info" role="alert">
                                No costs found.
                            </div>
                        @endif
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
