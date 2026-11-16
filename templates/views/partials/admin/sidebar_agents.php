<li class="nav-item <?= segment(2) === 'dashboard' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'agent/dashboard'?>">

        <?php if (segment(2) === 'dashboard') : ?>

            <img src="<?= THEME_DIR?>img/home_2.png" alt="Agent dashboard">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/home.png" alt="Agent dashboard">

        <?php endif ?>

        <span>Dashboard</span>

    </a>

</li>


<li class="nav-item">

    <a class="nav-link">

        <span class="insight">INSIGHTS</span>

    </a>

</li>

<li class="nav-item <?= segment(2) === 'performance' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'agent/performance'?>">

        <?php if (segment(2) === 'performance') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Performance">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leads.png" alt="Performance">

        <?php endif ?>
        
        <span>Performance</span>

    </a>
    
</li>

<li class="nav-item <?= segment(2) === 'leads' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'agent/leads'?>">

        <?php if (segment(2) === 'leads') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agent leads">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leads.png" alt="Agent leads">

        <?php endif ?>
        
        <span>Leads</span>

    </a>
    
</li>



<li class="nav-item <?= segment(2) === 'training' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'agent/training'?>">

        <?php if (segment(2) === 'training') : ?>

            <img src="<?= THEME_DIR?>img/training_2.png" alt="Training">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/training.png" alt="Training">

        <?php endif ?>
        
        <span>Training</span>

    </a>
    
</li>


<li class="nav-item">

    <a class="nav-link">

        <span class="system">SYSTEM</span>

    </a>

</li>

<li class="nav-item <?= segment(2) === 'help' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'agent/help'?>">

        <?php if (segment(2) === 'help') : ?>

            <img src="<?= THEME_DIR?>img/help_2.png" alt="Help">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/help.png" alt="Help">

        <?php endif ?>
        
        <span>Help</span>

    </a>
    
</li>





