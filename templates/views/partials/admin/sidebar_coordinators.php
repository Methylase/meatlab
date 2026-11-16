<li class="nav-item <?= segment(2) === 'dashboard' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'coordinator/dashboard'?>">

        <i class="fa fa-home" style="font-size:18px"></i>

        <span>Dashboard</span>

    </a>

</li>

<li class="nav-item <?= segment(2) === 'leads' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'coordinator/leads'?>">

        <?php if (segment(2) === 'leads') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agent leads">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leads.png" alt="Agent leads">

        <?php endif ?>
        
        <span>Leads</span>

    </a>
    
</li>

<li class="nav-item">

    <a class="nav-link">

        <span class="insight">INSIGHTS</span>

    </a>

</li>


<li class="nav-item <?= segment(2) === 'performance' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'coordinator/performance'?>">

        <?php if (segment(2) === 'performance') : ?>

            <img src="<?= THEME_DIR?>img/performance_2.png" alt="Performance">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/performance.png" alt="Performance">

        <?php endif ?>
        
        <span>Performance</span>

    </a>
    
</li>

<li class="nav-item <?= segment(2) === 'leaderboard' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'coordinator/leaderboard'?>">

        <?php if (segment(2) === 'leaderboard') : ?>

            <img src="<?= THEME_DIR?>img/leaderboard_2.png" alt="Leaderboard">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leaderboard.png" alt="Leaderboard">

        <?php endif ?>
        
        <span>Leaderboard</span>

    </a>
    
</li>
