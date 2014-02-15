<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/content/images') ?>" id="list"><?php echo lang('images_list'); ?></a>
	</li>
	<?php if ($this->auth->has_permission('Images.Content.Create')) : ?>
	<li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?> >
		<a href="<?php echo site_url(SITE_AREA .'/content/images/create') ?>" id="create_new"><?php echo lang('images_new'); ?></a>
	</li>
	<?php endif; ?>
</ul>