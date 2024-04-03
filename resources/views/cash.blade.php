@extends('layout.title')

@section('title', 'Cash Management')
@include('layout.title')

@include('layout.header')
<body>

  <!-- ======= Sidebar ======= -->
@include('layout.sidebar')

<main id="main" class="main" >

    <div class="pagetitle">
      <h1>Cash Management</h1>
      <nav class="custom-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Cash Management</li>
        </ol>
    </nav>
    </div><!-- End Page Title -->
    <section>
        <div >

<!-- If-else statement -->
@if(session('success'))
<div class="alert alert-success" role="alert">
    <strong>Success:</strong> {{ session('success') }}
</div>
@endif
<!-- Error message -->
@if($errors->any())
<div class="alert alert-danger" role="alert">
    <strong>Error:</strong>
        @foreach($errors->all() as $error)
       {{ $error }}
        @endforeach
</div>
@endif

<div class="container">
    <div class="row">
   <!-- Income and Outflow card -->
<div class="col-md-12 mb-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Revenue and Outflow</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Revenue</th>
                            <th scope="col">Outflow</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-success">
                                ${{ number_format($totalInflow, 2) }}
                            </td>
                            <td class="text-danger">
                                ${{ number_format($totalOutflow, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Income and Outflow card -->


                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card shadow" style="background-image: url('{{ asset('assets/img/budge.jpg') }}'); background-size: cover; opacity: 1;">
            <div class="card-header text-white"  style="background-color: rgba(255, 255, 255, 0.5);">
                <h5 class="mb-0 "><strong>Budget Requests</strong></h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="table-primary">
                                <th class="text-center">#</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($budgets ?? [] as $budget)
                                <tr>
                                    <td class="text-center">{{ $budget['id'] }}</td>
                                    <td>{{ $budget['title'] }}</td>
                                    <td>{{ $budget['status'] ?? 'Unknown' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#budgetModal{{ $budget['id'] }}">View</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No budgets found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    @foreach ($budgets ?? [] as $budget)
    <!-- Modal for budget details -->
    <div class="modal fade" id="budgetModal{{ $budget['id'] }}" tabindex="-1" aria-labelledby="budgetModalLabel{{ $budget['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="budgetModalLabel{{ $budget['id'] }}">Budget Invoice</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <h5 class="mb-4">Budget Details</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">Title:</dt>
                                    <dd class="col-sm-8">{{ $budget['title'] }}</dd>

                                    <dt class="col-sm-4">ID:</dt>
                                    <dd class="col-sm-8">{{ $budget['id'] }}</dd>

                                    <dt class="col-sm-4">Amount:</dt>
                                    <dd class="col-sm-8">{{ number_format($budget['amount'], 2) }}</dd>

                                    <dt class="col-sm-4">Status:</dt>
                                    <dd class="col-sm-8">{{ $budget['status'] ?? 'Unknown' }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-4">Additional Details</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">Category:</dt>
                                    <dd class="col-sm-8">{{ $budget['category'] ?? 'Not specified' }}</dd>

                                    <dt class="col-sm-4">Start Date:</dt>
                                    <dd class="col-sm-8">{{ $budget['start_date'] ?? 'Not specified' }}</dd>

                                    <dt class="col-sm-4">End Date:</dt>
                                    <dd class="col-sm-8">{{ $budget['end_date'] ?? 'Not specified' }}</dd>

                                    <!-- Add more fields as needed -->
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <!-- Form to allocate budget -->
                    @if ($budget['status'] === 'approved')
                        <form action="{{ route('budget.allocate', $budget['id']) }}" method="POST">
                            @csrf
                            <!-- Add hidden input field to pass budget data -->
                            <input type="hidden" name="budget" value="{{ json_encode($budget) }}">
                            <button type="submit" class="btn btn-success">Allocate Budget</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-secondary disabled" disabled>Cannot Allocate Budget</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for budget details -->
@endforeach

</div>
</section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
 @include('layout.footer')


</body>

</html>
