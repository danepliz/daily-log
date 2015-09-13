<div class="grid_12">
<h2><?php echo lang('configuration'); ?></h2>
	<div class="grid_8 prefix_2 suffix_2">
		<div class="section">
			<div class="launcher_icons">
				<?php if(!empty($launchers)) : ?>
				<ul>
					<?php foreach($launchers as $l):?>
					<li>
						<a href="<?php echo site_url($l['route']);?>">
							<img src="<?php echo $l['launcher_icon'];?>">
							<span><?php echo $l['label']?></span>
						</a>
					</li>
					<?php endforeach;?>
				</ul>
				<?php else: ?>
					<?php echo lang('permission_denied'); ?><a href="<?php echo base_url().'dashboard'?>">Go to dashboard</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>