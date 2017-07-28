<?php include_once __DIR__ . DS . 'helpers.php'; ?>


<div class="<?= $class ?>" data-js="photoswipe-gallery" itemscope itemtype="http://schema.org/ImageGallery" <?php e($combine, 'combine-galleries="true"') ?>>

  <!-- All images as <figure> elements -->
  <?php foreach($images as $idx => $image): ?>
    <?php
    // PhotoSwipe needs to know the image-dimensions for it's animation
    $dimensions = str_replace(" ", "", $image->dimensions());

    // Check if the image should be previewed
    $is_last_previewed = $idx == ($preview_count - 1);
    $is_not_previewed = $idx >= $preview_count;

    // Determine Column-Count of the row of the current image (see helpers.php)
    $col_class = columnClass('klg-cols-', $cols['min'], $cols['max'], $stretch, $stretch_last, $preview_count, $idx);
    $mobilecol_class = columnClass('klg-mobilecols-', $mobilecols['min'], $mobilecols['max'], $stretch, $stretch_last, $preview_count, $idx);
    ?>

    <figure class="<?php e($cols, $col_class) ?> <?php e($mobilecols, $mobilecol_class) ?>" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" data-count="<?= count($images) ?>" data-more-count="<?= count($images) - $idx - 1 ?>" <?php e($is_last_previewed, 'data-last-previewed') ?> <?php e($is_not_previewed, 'data-not-previewed') ?>>

      <a href="<?= $image->url() ?>" data-image="<?= $image->url() ?>" data-size="<?= $dimensions ?>" itemprop="contentUrl">

        <!-- Preview-Image as defined -->
        <?php if($thumb_provider == 'thumb'): ?>
          <?= $image->thumb($thumb_options) ?>
        <?php elseif($thumb_provider == 'focus'): ?>
          <?= $image->focusCrop(...$thumb_options) ?>
        <?php elseif($thumb_provider == 'imageset'): ?>
          <?= $image->imageset($thumb_options) ?>
        <?php else: ?>
          <?= $image ?>
        <?php endif ?>

        <!-- Image-Title & -Caption -->
        <figcaption itemprop="caption description">
          <?php if($image->title()->isNotEmpty()): ?>
            <h1><?= $image->title() ?></h1>
          <?php endif ?>

          <?= html::decode($image->caption()->kt()) ?>
        </figcaption>

      </a>

    </figure>

  <?php endforeach ?>

</div>
