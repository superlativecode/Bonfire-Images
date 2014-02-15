<?php

$validation_errors = validation_errors();

if ($validation_errors) :
?>
<div class="alert alert-block alert-error fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<h4 class="alert-heading">Please fix the following errors:</h4>
	<?php echo $validation_errors; ?>
</div>
<?php
endif;

if (isset($images))
{
	$images = (array) $images;
}
$id = isset($images['id']) ? $images['id'] : '';

?>
<div class="admin-box">
	<h3>Images</h3>
	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
		<fieldset>

			<div class="control-group <?php echo form_error('title') ? 'error' : ''; ?>">
				<?php echo form_label('Title'. lang('bf_form_label_required'), 'images_title', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<input id='images_title' type='text' name='images_title' maxlength="255" value="<?php echo set_value('images_title', isset($images['title']) ? $images['title'] : ''); ?>" />
					<span class='help-inline'><?php echo form_error('title'); ?></span>
				</div>
			</div>

			<div class="control-group <?php echo form_error('is_main') ? 'error' : ''; ?>">
				<?php echo form_label('Main Image', 'images_is_main', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<label class='checkbox' for='images_is_main'>
						<input type='checkbox' id='images_is_main' name='images_is_main' value='1' <?php echo (isset($images['is_main']) && $images['is_main'] == 1) ? 'checked="checked"' : set_checkbox('images_is_main', 1); ?>>
						<span class='help-inline'><?php echo form_error('is_main'); ?></span>
					</label>
				</div>
			</div>

			<div class="form-actions">
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('images_action_edit'); ?>"  />
				<?php echo lang('bf_or'); ?>
				<?php echo anchor(SITE_AREA .'/content/images', lang('images_cancel'), 'class="btn btn-warning"'); ?>
				
			<?php if ($this->auth->has_permission('Images.Content.Delete')) : ?>
				or
				<button type="submit" name="delete" class="btn btn-danger" id="delete-me" onclick="return confirm('<?php e(js_escape(lang('images_delete_confirm'))); ?>'); ">
					<span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('images_delete_record'); ?>
				</button>
			<?php endif; ?>
			</div>
		</fieldset>
    <?php echo form_close(); ?>
</div>