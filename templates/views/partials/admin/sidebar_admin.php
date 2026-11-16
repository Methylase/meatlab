<div class="sidebar-brand d-flex align-items-center justify-content-center">

    <div class="sidebar-brand-icon">

        <img class="img-logo" src="<?= BASE_URL?>images/kolosquare-logo-extra.png" alt="Company Logo">

    </div>

</div>

<?php 
   // $link = BASE_URL . segment(1) . '/dashboard';

?>



<?php if (segment(1) === 'admin') : ?>

<li class="nav-item">

    <a class="nav-link">

        <span class="overview">SETTINGS</span>

    </a>

</li>    

<?= Template::partial('partials/admin/sidebar_administrator') ?>

<?php endif ?>


<?php if (segment(1) === 'founders') : ?>

<li class="nav-item">

    <a class="nav-link">

        <span class="overview">OVERVIEW</span>

    </a>

</li>    

<?= Template::partial('partials/admin/sidebar_founders') ?>

<?php endif ?>


<?php if (segment(1) === 'national_team') : ?>

<li class="nav-item">

    <a class="nav-link">

        <span class="overview">OVERVIEW</span>

    </a>

</li>

<?= Template::partial('partials/admin/sidebar_nationals') ?>

<?php endif ?>



<?php if (segment(1) === 'manager') : ?>

<li class="nav-item">

    <a class="nav-link">

        <span class="overview">OVERVIEW</span>

    </a>

</li>    

<?= Template::partial('partials/admin/sidebar_managers') ?>

<?php endif ?>


<?php if (segment(1) === 'coordinator') : ?>

<li class="nav-item">

    <a class="nav-link">

        <span class="overview">OVERVIEW</span>

    </a>

</li>    

<?= Template::partial('partials/admin/sidebar_coordinators') ?>

<?php endif ?>


<?php if (segment(1) === 'agent') : ?>

<li class="nav-item">

<a class="nav-link">

    <span class="overview">OVERVIEW</span>

</a>

</li>   

<?= Template::partial('partials/admin/sidebar_agents') ?>

<?php endif ?>



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