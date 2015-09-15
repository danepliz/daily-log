<?php $activeDashboard = (current_url() == site_url('dashboard')) ? 'active' : ''; ?>

<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

    <div class="menu_section">
        <h3>&nbsp;</h3>
        <ul class="nav side-menu">
            <li><a href="<?php echo base_url().'dashboard' ?>"><i class="fa fa-home"></i> Dashboard</a></li>
            <?php echo \MainMenu::render();?>
        </ul>
    </div>

</div>
