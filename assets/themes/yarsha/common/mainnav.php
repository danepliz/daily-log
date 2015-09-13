<?php /* <div class="main_nav">
	<div class="grid_12">
		<ul id="navmenu">
 			<?php echo \MainMenu::render();?>
 			<?php if(config_access()):?>
 			<li class="mainconfig"><a href="<?php echo site_url('config');?>">Configuration</a></li>
 			<?php endif;?>
 		</ul>
	</div>
</div> */ ?>


<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
<!--    <div class="user-panel">-->
<!--        <div class="pull-left image">-->
<!--            <img class="img-circle" alt="User Image" />-->
<!--        </div>-->
<!--        <div class="pull-left info">-->
<!--            <p>Hello, --><?php //echo $current_user->getFullName() ?><!--</p>-->
<!---->
<!--            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
<!--        </div>-->
<!--    </div>-->

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
        <?php $activeDashboard = (current_url() == site_url('dashboard')) ? 'active' : ''; ?>
        <li class="<?php echo $activeDashboard ?>">
            <a href="<?php echo base_url().'dashboard' ?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>

        <?php echo \MainMenu::render();?>

<!--        <li>-->
<!--            <a href="pages/widgets.html">-->
<!--                <i class="fa fa-th"></i> <span>Widgets</span> <small class="badge pull-right bg-green">new</small>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li class="treeview">-->
<!--            <a href="#">-->
<!--                <i class="fa fa-bar-chart-o"></i>-->
<!--                <span>Charts</span>-->
<!--                <i class="fa fa-angle-left pull-right"></i>-->
<!--            </a>-->
<!--            <ul class="treeview-menu">-->
<!--                <li><a href="pages/charts/morris.html"><i class="fa fa-angle-double-right"></i> Morris</a></li>-->
<!--                <li><a href="pages/charts/flot.html"><i class="fa fa-angle-double-right"></i> Flot</a></li>-->
<!--                <li><a href="pages/charts/inline.html"><i class="fa fa-angle-double-right"></i> Inline charts</a></li>-->
<!--            </ul>-->
<!--        </li>-->
    </ul>
</section>

