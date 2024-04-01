@extends('layout.title')

@section('title', 'Financial Planning')

@include('layout.title')

@include('layout.header')

<body>

  <!-- ======= Sidebar ======= -->
@include('layout.sidebar')

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Financial Planning </h1>
      <nav class="custom-nav">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Financial Planning</a></li>
          <li class="breadcrumb-item active">Create Plan</li>
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

<div class="card">
    <div class="card-body">
        <div class="text-center">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExpenseCategoryModal">
                <i class="bi bi-file-text fs-1 d-block"></i>
                <span class="fs-6 d-block">Create Expense Category</span>
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCostAllocationMethodModal">
                <i class="bi bi-file-text fs-1 d-block"></i>
                <span class="fs-6 d-block">Create Cost Allocation Method</span>
            </button>
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
                        <label for="financial_planning_name" class="form-label">Financial Planning Name</label>
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




  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
 @include('layout.footer')
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
<!-- Cost Allocation Method Modal -->
<div class="modal fade" id="createCostAllocationMethodModal" tabindex="-1" aria-labelledby="createCostAllocationMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCostAllocationMethodModalLabel">Create Cost Allocation Method</h5>
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
                                            <select class="form-control" id="method_name" name="method_name">
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
                                        <button type="submit" class="btn btn-primary">Create Method</button>
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
<!-- Cost Allocation Method Modal -->
<div class="modal fade" id="createCostAllocationMethodModal" tabindex="-1" aria-labelledby="createCostAllocationMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createCostAllocationMethodModalLabel">Create Cost Allocation Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                    <button type="submit" class="btn btn-primary">Create Method</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Script to display method description based on selected method name
    document.getElementById('method_name').addEventListener('change', function() {
        var methodDescription = this.options[this.selectedIndex].getAttribute('data-description');
        document.getElementById('method_description').value = methodDescription;
    });
</script>

</body>

</html>
