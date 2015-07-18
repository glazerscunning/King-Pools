<div class="ngg_pro_proofing_confirmation">
    <?php foreach ($images as $image) { ?>
        <img src="<?php esc_attr_e($storage->get_image_url($image, 'thumb')); ?>"
             alt="<?php esc_attr_e($image->alttext); ?>"/>
    <?php } ?>
</div>
