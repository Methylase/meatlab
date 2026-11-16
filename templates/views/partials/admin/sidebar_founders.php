<li class="nav-item <?= segment(2) === 'dashboard' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL ?>founders/dashboard">

        <i class="fa fa-home" style="font-size:18px"></i>

        <span>Dashboard</span>

    </a>

</li>


<li class="nav-item <?= segment(2) === 'leads' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'founders/leads'?>">

        <?php if (segment(2) === 'leads') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agent leads">

        <?php else : ?>

           <img src="<?= THEME_DIR?>img/leads.png" alt="Agent leads">

        <?php endif ?>
        
        <span>Leads</span>

    </a>
    
</li>


<li class="nav-item <?= segment(2) === 'agents' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'founders/agents'?>">

        <?php if (segment(2) === 'training') : ?>

           <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agent leads">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leads.png" alt="Agent leads">

        <?php endif ?>
        
        <span>Agents</span>

    </a>
    
</li>




