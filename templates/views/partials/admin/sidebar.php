<a class="sidebar-brand d-flex align-items-center justify-content-center" href="">

    <div class="sidebar-brand-icon">

        <img class="img-logo" src="<?= THEME_DIR?>img/nldpi-logo.png" alt="Company Logo">

    </div>

</a>



<hr class="sidebar-divider my-0">



<li class="nav-item <?= segment(2) === 'dashboard' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . segment(1) . '/dashboard' ?>">

        <i class="fa fa-home" style="font-size:18px"></i>

        <span>Dashboard</span>

    </a>

</li>





<hr class="sidebar-divider custom-divider">


<li class="nav-item ">

    <a class="nav-link" href="users/logout" data-toggle="modal" data-target="#logoutModal">

        <i class="fa fa-sign-out-alt" style="font-size:18px"></i>

        <span>Log Out</span>

    </a>

</li>



<div class="text-center d-none d-md-inline">

    <button class="rounded-circle border-0" id="sidebarToggle"></button>

</div>