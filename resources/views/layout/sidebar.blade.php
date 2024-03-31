<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav"  href="{{route('Financial.Planning')}}">
            <i class="bi bi-file-earmark-text"></i><span>Financial Planning</span>
        </a>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav"  href="{{route('cash.index')}}">
            <i class="bi bi-cash"></i><span>Cash Management</span>
        </a>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav"  href="{{route('report.index')}}">
            <i class="bi bi-newspaper"></i><span>Financial Report</span>
        </a>
      </li><!-- End Components Nav -->


    </ul>

  </aside><!-- End Sidebar-->
