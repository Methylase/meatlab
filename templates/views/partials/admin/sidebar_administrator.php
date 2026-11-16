
<li class="nav-item <?= segment(2) === 'management' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'admin/management'?>">

        <?php if (segment(2) === 'management') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Management User">

        <?php else : ?>

           <img src="<?= THEME_DIR?>img/leads.png" alt="Management User">

        <?php endif ?>
        
        <span>Management</span>

    </a>
    
</li>


<li class="nav-item <?= segment(2) === 'agents' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'admin/agents'?>">

        <?php if (segment(2) === 'agents') : ?>

           <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agents">

        <?php else : ?>

            <img src="<?= THEME_DIR?>img/leads.png" alt="Agents">

        <?php endif ?>
        
        <span>Agents</span>

    </a>
    
</li>


<li class="nav-item <?= segment(2) === 'leads' ? 'active' : '' ?>">

    <a class="nav-link" href="<?= BASE_URL . 'admin/leads'?>">

        <?php if (segment(2) === 'leads') : ?>

            <img src="<?= THEME_DIR?>img/leads_2.png" alt="Agent leads">

        <?php else : ?>

           <img src="<?= THEME_DIR?>img/leads.png" alt="Agent leads">

        <?php endif ?>
        
        <span>Leads</span>

    </a>
    
</li>







