<?php

/**
* Lightbox-Gallery Plugin
*
* @package   Kirby CMS
* @author    Dennis Kerzig <hi@wottpal.com>
* @version   0.3.0
*/


// Helpers
include_once __DIR__ . DS . 'helpers.php';


// Dynamic Tagname
$tagname = c::get('lightboxgallery.kirbytext.tagname', 'gallery');


$kirby->set('tag', $tagname, [
  'attr' => [ 'limit', 'stretch', 'cols', 'mobilecols', 'id', 'class', 'page' ],

  'html' => function($tag) use ($tagname) {
    // Options
    $class = $tag->attr('class', c::get('lightboxgallery.class', ''));
    $id = $tag->attr('id', c::get('lightboxgallery.id', ''));
    $combine = c::get('lightboxgallery.combine', false);
    $limit = $tag->attr('limit', c::get('lightboxgallery.limit', false));

    // Determine Stretch-Properties
    $stretch = strtolower($tag->attr('stretch', c::get('lightboxgallery.stretch', 'first')));
    $stretch_last = $stretch === 'last';
    $stretch = $stretch === 'last' || $stretch === 'first';

    // Columns-Definition (get it from config or as tag-attribute)
    $cols = c::get('lightboxgallery.cols', ['min' => 3, 'max' => 4]);
    $cols_attr = columnsFromString($tag->attr('cols', false));
    if ($cols_attr) $cols = $cols_attr;

    $mobilecols = c::get('lightboxgallery.mobilecols', ['min' => 2, 'max' => 2]);
    $mobilecols_attr = columnsFromString($tag->attr('mobilecols', false));
    if ($mobilecols_attr) $mobilecols = $mobilecols_attr;

    // Allow other pages as file-source (esp. for the `kirbytag` function)
    $source = $tag->page();
    $custom_page = $tag->attr('page', false);
    if ($custom_page) $custom_page = page($custom_page);
    if ($custom_page) $source = $custom_page;

    // Thumb-Options
    $thumb_provider = strtolower(c::get('lightboxgallery.thumb.provider', 'thumb'));
    $thumb_options = c::get('lightboxgallery.thumb.options', [
      "width" => 800,
      "height" => 800,
      "crop" => true
    ]);

    // Ensure necessary ’thumb.options' are given
    $thumb_options_given = c::get('lightboxgallery.thumb.options', false) != false;
    if ($thumb_provider && $thumb_provider != 'thumb' && !$thumb_options_given) {
      throw new Exception("If something else than 'thumb' is set as 'thumb.provider', 'thumb.options' has to be specified, too.");
    }

    // Gather Images
    $images_string = $tag->attr($tagname);
    $use_all = strtolower(trim($images_string)) === 'all';
    $images_string = str::split($images_string, ' ');
    $images = $use_all ? $source->images()->keys() : $images_string;
    $image_files = [];
    foreach ($images as $image) {
      $image_file = $source->file(trim($image));
      if ($image_file) array_push($image_files, $image_file);
    }

    // Determine Count of displayed Thumbnails
    $use_limit = $limit && $limit < count($image_files);
    $preview_count = $use_limit ? $limit : count($image_files);

    if (!empty($image_files)) {
      // Create Gallery-HTML from Template
      $gallery = tpl::load(__DIR__ . DS . 'template.php', [
        'images' => $image_files,
        'id' => $id,
        'class' => $class,
        'combine' => $combine,
        'thumb_provider' => $thumb_provider,
        'thumb_options' => $thumb_options,
        'preview_count' => $preview_count,
        'cols' => $cols,
        'mobilecols' => $mobilecols,
        'stretch' => $stretch,
        'stretch_last' => $stretch_last
      ], true);

      return html($gallery);
    }
  }
]);
