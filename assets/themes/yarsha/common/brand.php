<?php

use user\models\User;
?>


<header class="header">
<a href="<?php echo base_url();?>" class="logo" style="background: #FFF">
    <img src="<?php echo base_url().'assets/themes/yarsha/resources/images/brand.png' ?>" class="icon" />
<!--   TOUR MGMT-->
</a>


<nav class="navbar navbar-static-top" role="navigation">

<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</a>

<div class="navbar-right">
<ul class="nav navbar-nav">

<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="glyphicon glyphicon-user"></i>

        <span><?php echo $current_user->getFullName(); ?> <i class="caret"></i></span>
    </a>
    <ul class="dropdown-menu">

        <li class="user-header bg-yellow">
<!--            <img  class="img-circle" alt="User Image" />-->
            <p> <?php  echo $current_user->getFullName().'<small>'.$current_user->getEmail().'</small>'; ?> </p>
        </li>

        <?php
            $accountMenu = array(
                'Profile' => 'user/profile',
                'Change Password' => 'user/changepwd'
            );

            foreach($accountMenu as $k => $v){
                $link = base_url().$v;
                echo '<li class="user-body"><div class="col-xs-12 text-center"><a href="'.$link.'">'.$k.'</a></div></li>';
            }

        ?>

        <li class="user-footer">
            <div class="col-xs-12 text-center">
                <a href="<?php echo base_url().'auth/logout' ?>" class="col-xs-12 btn btn-danger">Logout</a>
            </div>
        </li>
    </ul>
</li>
</ul>
</div>
</nav>
</header>