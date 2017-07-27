<?php

/**
* Lightbox-Gallery Plugin
*
* @package   Kirby CMS
* @author    Dennis Kerzig <hi@wottpal.com>
* @version   0.0.1
*/


// Dynamic Tagname
$tagname = c::get('lightboxgallery.kirbytext.tagname', 'gallery');

$kirby->set('tag', $tagname, [
  'attr' => ['max'],
  'html' => function($tag) use ($tagname) {
    // Options
    $class = c::get('lightboxgallery.class', '');
    $combine = c::get('lightboxgallery.combine', false);
    $use_imageset = c::get('lightboxgallery.imageset', false);
    $imageset_thumb_preset = c::get('lightboxgallery.imageset.thumb.preset', false);
    $max_preview = $tag->attr('max', c::get('lightboxgallery.max.preview', false));
    $cols = c::get('lightboxgallery.cols', ['min' => 3, 'max' => 4]);
    $mobilecols = c::get('lightboxgallery.mobilecols', ['min' => 2, 'max' => 2]);

    // Only Use Imageset if Thumb-Preset is given
    $use_imageset = $use_imageset && $imageset_thumb_preset;

    // Gather Images
    $images = str::split($tag->attr($tagname), ' ');
    $image_files = [];
    foreach ($images as $image) {
      $image_file = $tag->file(trim($image));
      if ($image_file) array_push($image_files, $image_file);
    }

    // Determine Count of Image-Previews
    $use_max_preview = $max_preview && $max_preview < count($image_files);
    $preview_count = $use_max_preview ? $max_preview : count($image_files);

    if (!empty($image_files)) {
      // Create Gallery-HTML from Template
      $gallery = tpl::load(__DIR__ . DS . 'template.php', [
        'class' => $class,
        'combine' => $combine,
        'images' => $image_files,
        'use_imageset' => $use_imageset,
        'imageset_thumb_preset' => $imageset_thumb_preset,
        'preview_count' => $preview_count,
        'cols' => $cols,
        'mobilecols' => $mobilecols
      ], true);

      return html($gallery);
    }
  }
]);
