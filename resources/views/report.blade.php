@extends('layout.title')

@section('title', 'Financial Planning & Reporting')
@include('layout.title')


<body>
    @include('layout.header')
  <!-- ======= Sidebar ======= -->
@include('layout.sidebar')

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Financial Planning & Reporting</h1>
      <nav class="custom-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Financial Planning & Reporting</li>
        </ol>
    </nav>
    </div><!-- End Page Title -->

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
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="container mt-12">
    <div class="row">
        <!-- Financial Health Status card -->
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Financial Health Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Description</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Revenue</td>
                                        <td>{{ number_format($totalInflow, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Expenses</td>
                                        <td>{{ number_format($totalOutflow, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Net Income</td>
                                        <td>{{ number_format($netIncome, 2) }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>Note:</strong> This financial health status is based on the calculation of Net Income.
                            </div>
                            <h4 class="text-right mb-0">Net Income: <span class="{{ $netIncomeStatus }}">{{ number_format($netIncome, 2) }}</span></h4>
                            <p class="text-right">
                                @if ($netIncomeStatus == 'status-good')
                                    Congratulations! Your financial health status is good.
                                @elseif ($netIncomeStatus == 'status-ok')
                                    Your financial health status is okay. Consider improving it.
                                @else
                                    Warning! Your financial health status is not good. Take necessary actions.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Financial Health Status card -->

        <!-- Create Cost Allocation Method Button -->
        <div class="col-md-3 mb-3">
            <div class="card">
                <button type="button" class="btn btn-primary btn-sm w-100 position-relative" data-bs-toggle="modal" data-bs-target="#createCostAllocationMethodModal">
                    <img src="{{ asset('assets/img/paying.jpg') }}" alt="Image" class="img-fluid mb-1">
                    <div class="position-absolute top-50 start-50 translate-middle">

                        <span class="fs-6"><strong>Create Cost Allocation Method</strong></span>
                    </div>
                </button>
            </div>
        </div>
        <!-- End Create Cost Allocation Method Button -->

        <!-- Create Expense Category Button -->
        <div class="col-md-3 mb-3">
            <div class="card">
                <button type="button" class="btn btn-primary btn-sm w-100 position-relative" data-bs-toggle="modal" data-bs-target="#createExpenseCategoryModal">
                    <img src="{{ asset('assets/img/exp.jpg') }}" alt="Image" class="img-fluid">
                    <div class="position-absolute top-50 start-50 translate-middle">

                        <span class="fs-6"><strong>Create Expense Category</strong></span>
                    </div>
                </button>
            </div>
        </div>
        <div class="col-md-3 mb-3">

        <!-- End Create Expense Category Button -->
        <canvas id="pieChart" width="400" height="400"></canvas>
    </div>


        <script>
            var data = @json($data);
            var labels = Object.keys(data);
            var values = Object.values(data);

            var ctx = document.getElementById('pieChart').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Allocation Summary'
                    }
                }
            });
        </script>
    </div>

  <!-- Financial Report Document -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card bg-light"  style="background-image: url('{{ asset('assets/img/reportcost.jpg') }}'); background-size: cover;">
            <div class="card-header" style="background-color: rgba(255, 255, 255, 0.5);">
                <h3 class="card-title"><strong>Financial Report: Cost Allocation</strong></h3>
            </div>
            <div class="card-body ">

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
<!-- End Financial Report Document -->

</div>


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->



<!-- Cost Allocation Method Modal -->
<div class="modal fade" id="createCostAllocationMethodModal" tabindex="-1" aria-labelledby="createCostAllocationMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createCostAllocationMethodModalLabel">Create Financial Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('cost_allocation_methods.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="financial_plan_name" class="form-label">Financial Plan Name</label>
                                            <input type="text" class="form-control" id="financial_plan_name" name="financial_plan_name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="method_name" class="form-label">Method Name</label>
                                            <select class="form-select" id="method_name" name="method_name">
                                                <option value="Direct Method" data-description="Allocates costs directly to cost centers without any allocation of service department costs. It's simple and easy to understand but may not accurately reflect the actual consumption of resources.">Direct Method</option>
                                                <option value="Step-down Method" data-description="Allocates service department costs in a sequential manner, starting with one service department and then allocating to others. It provides a more accurate allocation of costs than the direct method but can still be simplistic.">Step-down Method</option>
                                                <option value="Reciprocal Method" data-description="Allocates service department costs based on mutual services received. It considers the interdependencies between service departments but can be complex and time-consuming to implement.">Reciprocal Method</option>
                                                <option value="Activity-Based Costing" data-description="Allocates costs based on the activities that drive those costs. It provides more accurate cost allocations by identifying the specific activities that consume resources.">Activity-Based Costing</option>
                                                <option value="Absorption Costing" data-description="Allocates all manufacturing costs to products, including both variable and fixed costs. It's commonly used for external financial reporting but may not provide accurate insights for decision-making.">Absorption Costing</option>
                                                <option value="Marginal Costing" data-description="Allocates only variable manufacturing costs to products, while fixed costs are treated as period expenses. It helps in decision-making by focusing on the incremental costs of producing additional units.">Marginal Costing</option>
                                                <option value="Standard Costing" data-description="Allocates costs based on predetermined standard costs for materials, labor, and overhead. It helps in performance evaluation by comparing actual costs with standard costs.">Standard Costing</option>
                                                <option value="Activity-Based Budgeting" data-description="Allocates resources based on the activities required to achieve organizational goals. It aligns budgeting with strategic objectives and focuses on the value-added activities.">Activity-Based Budgeting</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="method_description" class="form-label">Method Description</label>
                                            <textarea class="form-control" id="method_description" name="method_description" rows="3" readonly></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Create Plan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Expense Category Modal -->
<div class="modal fade" id="createExpenseCategoryModal" tabindex="-1" aria-labelledby="expenseCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="expenseCategoryModalLabel">Create Expense Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('expense_categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="financial_planning_name" class="form-label">Item</label>
                        <input type="text" class="form-control" id="financial_planning_name" name="financial_planning_name">
                    </div>
                    <div class="mb-3">
                        <label for="expense_category" class="form-label">Expense Category</label>
                        <select class="form-select" id="expense_category" name="expense_category">
                            <option value="Utilities" data-description="Expenses related to electricity, water, gas, and other essential services required for operating the business.">Utilities</option>
                            <option value="Rent/Mortgage" data-description="Payments for leasing or owning office space, retail locations, warehouses, or other business premises.">Rent/Mortgage</option>
                            <option value="Salaries and Wages" data-description="Compensation paid to employees for their work, including salaries, wages, bonuses, and commissions.">Salaries and Wages</option>
                            <option value="Employee Benefits" data-description="Costs associated with providing employee benefits such as health insurance, retirement plans, paid time off, and other perks.">Employee Benefits</option>
                            <option value="Supplies" data-description="Expenses for office supplies, stationery, equipment, and other materials necessary for day-to-day operations.">Supplies</option>
                            <option value="Inventory" data-description="Costs related to purchasing and maintaining inventory, including raw materials, finished goods, and work-in-progress.">Inventory</option>
                            <option value="Marketing and Advertising" data-description="Expenditures on advertising campaigns, promotional materials, website development, and other marketing initiatives to attract customers and promote the business.">Marketing and Advertising</option>
                            <option value="Travel and Entertainment" data-description="Costs associated with business travel, meals, entertainment, and other expenses incurred while conducting business activities.">Travel and Entertainment</option>
                            <option value="Professional Services" data-description="Fees paid to consultants, lawyers, accountants, and other professional service providers for their expertise and assistance.">Professional Services</option>
                            <option value="Insurance" data-description="Premiums for business insurance policies, including property insurance, liability insurance, and other types of coverage to protect against risks and liabilities.">Insurance</option>
                            <option value="Maintenance and Repairs" data-description="Expenses for maintaining and repairing equipment, vehicles, machinery, and facilities to ensure they remain operational and in good condition.">Maintenance and Repairs</option>
                            <option value="Taxes and Licenses" data-description="Payments for taxes, licenses, permits, and regulatory fees required to operate the business legally and comply with government regulations.">Taxes and Licenses</option>
                            <option value="Interest" data-description="Interest payments on loans, lines of credit, and other forms of debt used to finance business operations or investments.">Interest</option>
                            <option value="Depreciation" data-description="Allocation of the cost of tangible assets over their useful lives as an expense on the income statement.">Depreciation</option>
                            <option value="Miscellaneous" data-description="Other expenses that do not fit into specific categories but are necessary for running the business, such as bank fees, subscriptions, and office maintenance.">Miscellaneous</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="expense_description" class="form-label">Expense Description</label>
                        <textarea class="form-control" id="expense_description" name="expense_description" rows="3" readonly></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Get the expense category select element
    const expenseCategorySelect = document.getElementById('expense_category');

    // Get the expense description textarea
    const expenseDescriptionTextarea = document.getElementById('expense_description');

    // Add event listener to the expense category select element
    expenseCategorySelect.addEventListener('change', function() {
        // Get the selected option
        const selectedOption = expenseCategorySelect.options[expenseCategorySelect.selectedIndex];

        // Update the description textarea with the description of the selected option
        expenseDescriptionTextarea.value = selectedOption.getAttribute('data-description');
    });
</script>
 <script>
    // Script to display method description based on selected method name
    document.getElementById('method_name').addEventListener('change', function() {
        var methodDescription = this.options[this.selectedIndex].getAttribute('data-description');
        document.getElementById('method_description').value = methodDescription;
    });
</script>
@include('layout.footer')
</body>

</html>
