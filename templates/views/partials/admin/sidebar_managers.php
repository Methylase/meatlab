

<li class="nav-item <?= segment(2) === 'war_map' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'manager/war_map'?>">

        <?php if (segment(2) === 'war_map') : ?>

            <img src="<?= THEME_DIR?>img/performance_2.png" alt="Performance">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/performance.png" alt="Performance">

        <?php endif ?>
        
        <span>Performance</span>

    </a>
    
</li>

<li class="nav-item <?= segment(2) === 'agents' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'manager/agents'?>">

        <?php if (segment(2) === '') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agents">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agents">

        <?php endif ?>
        
        <span>Agents</span>

    </a>
    
</li>

<li class="nav-item <?= segment(2) === 'leads' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'manager/leads'?>">

        <?php if (segment(2) === '') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Leads">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Leads">

        <?php endif ?>
        
        <span>Leads</span>

    </a>
    
</li>

<li class="nav-item">

    <a class="nav-link">

        <span class="system">SYSTEM</span>

    </a>

</li>

<li class="nav-item <?= segment(2) === 'help' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'manager/help'?>">

        <?php if (segment(2) === 'help') : ?>

            <img src="<?= THEME_DIR?>img/help_2.png" alt="Help">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/help.png" alt="Help">

        <?php endif ?>
        
        <span>Help</span>

    </a>
    
</li>

