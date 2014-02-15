    <h3>Images</h3>
    <table class="table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Is Main</th>
                <th>Image URL</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="images_list">
            <?php if(!empty($images) && is_array($images)): ?> 
                <?php foreach($images as $img): ?>
                    <?php $this->load->view('images/content/image_row', array('image' => $img, 'type' => 'none')); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr id="images_empty">
                    <td colspan="5">No Images attached. Use dropzone below for uploading.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
