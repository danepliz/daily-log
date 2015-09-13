<div class="topbar">
	<div class="grid_3 suffix_6 date">
		<p>Thursday, 22 nd March 2012 11:55:48</p>
	</div>

	<div class="grid_3">
		<a href="<?php echo site_url('config')?>">Configuration</a>
		<div class="admin">
			<p>
				<span class="left">Welcome back, <?php echo Current_User::user()->getFirstname();?>!</span>
				<span class="right"><a href="<?php echo site_url('auth/logout')?>">Logout</a></span>
			</p>
		</div>
	</div>
	<div class="clear"></div>
</div>