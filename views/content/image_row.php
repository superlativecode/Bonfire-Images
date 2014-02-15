<?php

$can_delete	= $this->auth->has_permission('Images.Content.Delete');
$can_edit = $this->auth->has_permission('Images.Content.Edit');

?>

<tr data-id="<?=$image->id?>" style="background-color: #f1f1f1;">
    <td>
        <?php if(isset($type) && $type == 'new'): ?>
        <input type="hidden" name="new_image_id[]" value="<?php e($image->id); ?>" />
        <?php endif; ?>
        <img src="<?=$image->thumb_url?>" alt="<?=$image->title?>"/>
    </td>
    <?php if ($can_edit) : ?>
    	<td><input type="text" name="images_title_<?=$image->id?>" value="<?php e($image->title); ?>" /></td>
    	<td><input type="checkbox" name="images_is_main_<?=$image->id?>" value="1" <?=($image->is_main ? 'checked="checked"' : '')?>  /></td>
    <?php else : ?>
    	<td><?php e($image->title); ?></td>
    	<td><?=($image->is_main ? 'True' : 'False')?></td>
    <?php endif; ?>
    <td><?php e($image->image_url); ?></td>
    <td>
        <?php if ($can_edit) : ?>
    		<button class="btn btn-primary save-image" onclick="return false;"></span><?php echo "Save Image"; ?></button><br />
    	<?php endif; ?>
        <?php if ($can_delete) : ?>
    		<button class="btn btn-danger delete-image" onclick="return false;">
    			<span class="icon-trash icon-white"></span><?php echo "Delete Image"; ?>
    		</button>
    	<?php endif; ?>	
	<td>
</tr>