<?php

/**
* Lightbox-Gallery Plugin
*
* @package   Kirby CMS
* @author    Dennis Kerzig <hi@wottpal.com>
*/

include_once __DIR__ . DS . 'helpers.php';

Kirby::plugin('wottpal/lightbox-gallery', [
    'tags' => [
        option('wottpal.lightboxgallery.tag', 'gallery') => [
            'attr' => [
                'limit',
                'stretch',
                'cols',
                'mobilecols',
                'id',
                'class',
                'page',
                'order'
            ],
            'html' => function ($tag) {
                // Options
                $class = $tag->attr('class', option('wottpal.lightboxgallery.class', ''));
                $id = $tag->attr('id', option('wottpal.lightboxgallery.id', ''));
                $combine = option('wottpal.lightboxgallery.combine', false);
                $limit = $tag->attr('limit', option('wottpal.lightboxgallery.limit', false));

                // Determine Stretch-Properties
                $stretch = strtolower($tag->attr('stretch', option('wottpal.lightboxgallery.stretch', 'first')));
                $stretch_last = $stretch === 'last';
                $stretch = $stretch === 'last' || $stretch === 'first';

                // Columns-Definition (get it from config or as tag-attribute)
                $cols = option('wottpal.lightboxgallery.cols', [
                    'min' => 3,
                    'max' => 4
                ]);
                $cols_attr = columnsFromString($tag->attr('cols', false));
                if ($cols_attr) $cols = $cols_attr;
                
                $mobilecols = c::get('lightboxgallery.mobilecols', [
                    'min' => 2,
                    'max' => 2
                ]);
                $mobilecols_attr = columnsFromString($tag->attr('mobilecols', false));
                if ($mobilecols_attr) $mobilecols = $mobilecols_attr;
                
                // Allow other pages as file-source (esp. for the `kirbytag` function)
                $source = $tag->parent();
                $custom_page = $tag->attr('page', false);
                if ($custom_page) $custom_page = page($custom_page);
                if ($custom_page) $source = $custom_page;
                
                $source_slug = $source->slug();
                
                // Title and caption field name options
                $field_title = strtolower(option('wottpal.lightboxgallery.field.title', 'title'));
                $field_caption = strtolower(option('wottpal.lightboxgallery.field.caption', 'caption'));

                // Dominant color option
                $dominant_color = option('wottpal.lightboxgallery.field.dominantcolor', false);
                $dominant_color_name = option('wottpal.lightboxgallery.field.dominantcolor.name', 'color');

                // Thumb-Options
                $thumb_provider = strtolower(option('wottpal.lightboxgallery.thumb.provider', 'thumb'));
                $thumb_options = option('wottpal.lightboxgallery.thumb.options', [
                    "width" => 800,
                    "height" => 800,
                    "crop" => true
                ]);
                
                // Ensure necessary ’thumb.options' are given
                $thumb_options_given = option('wottpal.lightboxgallery.thumb.options', false) != false;
                if ($thumb_provider && $thumb_provider != 'thumb' && !$thumb_options_given) {
                    throw new Exception("If something else than 'thumb' is set as 'thumb.provider', 'thumb.options' has to be specified, too.");
                }
                
                // Gather Images
                $images_string = $tag->attr(option('wottpal.lightboxgallery.tag', 'gallery'));
                $use_all = strtolower(trim($images_string)) === 'all';
                $images_string = str::split($images_string, ' ');
                $images = $use_all ? $source->images()->keys() : $images_string;
                $image_files = [];
                foreach ($images as $image) {
                    $pathElements = explode('/', $image);
                    $name = end($pathElements);
                    $image_file = $source->file(trim($name));
                    if ($image_file) array_push($image_files, $image_file);
                }
                
                // Order Images
                $order = strtolower($tag->attr('order', 'default'));
                if ($order === 'reverse') $image_files = array_reverse($image_files);
                if ($order === 'random' || $order === 'shuffle') shuffle($image_files);
                
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
                        'field_caption' => $field_caption,
                        'field_title' => $field_title,
                        'thumb_provider' => $thumb_provider,
                        'thumb_options' => $thumb_options,
                        'preview_count' => $preview_count,
                        'field_color' => $dominant_color_name,
                        'use_color' => $dominant_color,
                        'cols' => $cols,
                        'mobilecols' => $mobilecols,
                        'stretch' => $stretch,
                        'stretch_last' => $stretch_last
                    ], true);
                    
                    return $gallery;
                }
            }
        ]
    ]
]);
