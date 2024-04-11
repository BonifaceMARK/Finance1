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
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#financialReportModal">
     Expenses Financial Report
</button>


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#costAllocationModal">
     Cost Allocation Report
</button>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#budgetReportModal">
         Budget Report
    </button>

    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clientsModal">
     Risk Management Report
</button>

<!-- Modal -->
<div class="modal fade" id="clientsModal" tabindex="-1" aria-labelledby="clientsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientsModalLabel">Clients Risk Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client Name</th>
                                <th>Company Name</th>
                                <th>Risk Manager</th>
                                <th>Commercial Budget</th>
                                <th>Industry Sector</th>
                                <th>Description</th>
                                <th>Facility</th>
                                <th>Risk Owner</th>
                                <th>Date Raised</th>
                                <th>Risk Occurrence</th>
                                <th>Risk Bearer</th>
                                <th>Probability</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client['id'] }}</td>
                                    <td>{{ $client['client_name'] }}</td>
                                    <td>{{ $client['company_name'] }}</td>
                                    <td>{{ $client['risk_manager'] }}</td>
                                    <td>{{ $client['commercial_budget'] }}</td>
                                    <td>{{ $client['industry_sector'] }}</td>
                                    <td>{{ $client['description'] }}</td>
                                    <td>{{ $client['facility'] }}</td>
                                    <td>{{ $client['risk_owner'] }}</td>
                                    <td>{{ $client['date_raised'] }}</td>
                                    <td>{{ $client['risk_occurrence'] }}</td>
                                    <td>{{ $client['risk_bearer'] }}</td>
                                    <td>{{ $client['probability'] }}</td>
                                    <td>{{ $client['status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <canvas id="probabilityChart" width="400" height="300"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    </nav>
    </div><!-- End Page Title -->
   <!-- JavaScript for Chart.js -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script>
       // Get probability values from clients data
       var probabilities = @json(array_column($clients, 'probability'));

       // Create a chart
       var ctx = document.getElementById('probabilityChart').getContext('2d');
       var myChart = new Chart(ctx, {
           type: 'doughnut',
           data: {
               labels: probabilities.map((_, i) => 'Risk ' + (i + 1)),
               datasets: [{
                   label: 'Probability',
                   data: probabilities,
                   backgroundColor: 'rgba(54, 162, 235, 0.5)',
                   borderColor: 'rgba(54, 162, 235, 1)',
                   borderWidth: 1
               }]
           },
           options: {
               scales: {
                   y: {
                       beginAtZero: true
                   }
               }
           }
       });
   </script>

<!-- Modal -->
<div class="modal fade" id="budgetReportModal" tabindex="-1" aria-labelledby="budgetReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="budgetReportModalLabel">Budget Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row justify-content-center">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Budget Report Table</div>
                                <div class="card-body">
                                    <!-- Budget Report Table -->
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Reference</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Status</th>
                                                    <th>Comment</th>
                                                    <th>Created By</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($budget as $item)
                                                <tr>
                                                    <td>{{ $item['id'] }}</td>
                                                    <td>{{ $item['reference'] }}</td>
                                                    <td>{{ $item['title'] }}</td>
                                                    <td>{{ $item['description'] }}</td>
                                                    <td>{{ $item['amount'] }}</td>
                                                    <td>{{ $item['start_date'] }}</td>
                                                    <td>{{ $item['end_date'] }}</td>
                                                    <td>{{ $item['status'] }}</td>
                                                    <td>{{ $item['comment'] }}</td>
                                                    <td>{{ $item['name'] }}</td>
                                                    <td>{{ $item['created_at'] }}</td>
                                                    <td>{{ $item['updated_at'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Budget Chart</div>
                                <div class="card-body">
                                    <!-- Chart will be populated by JavaScript -->
                                    <canvas id="budgetChart" width="400" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for fetching data and rendering chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fetch data from external API for the chart
        fetch('https://fms2-ecabf.fguardians-fms.com/api/budgetApi')
            .then(response => response.json())
            .then(data => {
                // Extract labels and data for the chart
                const labels = data.map(item => item.title);
                const amounts = data.map(item => parseFloat(item.amount));

                // Render chart
                const ctx = document.getElementById('budgetChart').getContext('2d');
                const budgetChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Amount',
                            data: amounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    });
</script>



<!-- Modal -->
<div class="modal fade" id="costAllocationModal" tabindex="-1" aria-labelledby="costAllocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card bg-light"  style="background-image: url('{{ asset('assets/img/reportcost.jpg') }}'); background-size: cover;">
                    <div class="card-header" style="background-color: rgba(255, 255, 255, 0.5);">
                        <h3 class="card-title"><strong>Financial Report: Cost Allocation</strong></h3>
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
                                                <td>{{ $cost['cost_type'] }}</td>
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
                <canvas id="costAllocationChart" width="600" height="200"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for fetching data and rendering chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fetch data from external API for the chart
        fetch('https://fms2-ecabf.fguardians-fms.com/api/costApi')
            .then(response => response.json())
            .then(data => {
                // Extract data for the chart
                const labels = data.map(cost => cost.cost_center);
                const amounts = data.map(cost => parseFloat(cost.amount));

                // Render chart
                const ctx = document.getElementById('costAllocationChart').getContext('2d');
                const costAllocationChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Allocated Cost',
                            data: amounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    });
</script>
<!-- Modal -->
<div class="modal fade" id="financialReportModal" tabindex="-1" aria-labelledby="financialReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="financialReportModalLabel">Financial Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense['id'] }}</td>
                            <td>{{ $expense['item'] }}</td>
                            <td>{{ $expense['date'] }}</td>
                            <td>{{ $expense['amount'] }}</td>
                            <td>{{ $expense['category'] }}</td>
                            <td>{{ $expense['description'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <canvas id="financialReportChart" width="600" height="200"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for fetching data and rendering the chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('https://fms2-ecabf.fguardians-fms.com/api/expensesApi')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(expense => expense.item);
                const amounts = data.map(expense => parseFloat(expense.amount));

                const ctx = document.getElementById('financialReportChart').getContext('2d');
                const financialReportChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Expenses Amount',
                            data: amounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    });
</script>
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
