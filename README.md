# 🖼 Kirby Lightbox-Gallery by [@wottpal](https://twitter.com/wottpal)

<!-- Buttons -->
![Release](https://img.shields.io/github/release/wottpal/kirby-lightbox-gallery/all.svg)
[![MIT](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/wottpal/kirby-lightbox-gallery/master/LICENSE)
[![Tweet](https://img.shields.io/twitter/url/https/github.com/wottpal/kirby-lightbox-gallery.svg?style=social)](https://twitter.com/intent/tweet?text=&#x1F5BC;&#x20;&#x4C;&#x69;&#x67;&#x68;&#x74;&#x62;&#x6F;&#x78;&#x2D;&#x47;&#x61;&#x6C;&#x6C;&#x65;&#x72;&#x79;&#x20;&#x66;&#x6F;&#x72;&#x20;&#x40;&#x67;&#x65;&#x74;&#x6B;&#x69;&#x72;&#x62;&#x79;&#x20;&#x62;&#x79;&#x20;&#x40;&#x77;&#x6F;&#x74;&#x74;&#x70;&#x61;&#x6C;&url=https://git.io/v7ajs)
 ![Kirby](https://img.shields.io/badge/Kirby-3.x-f0c674.svg)


(_Disclaimer:_ This is a pre-release.)

A plugin for the awesome [Kirby CMS](http://getkirby.com) which allows the editor to easily inline beautiful galleries with lightbox-support.

* **Dynamic & responsive Grid-Alignment**
* **Works with Kirby's built-in thumbnail class, [ImageSet](https://github.com/fabianmichael/kirby-imageset) by @fabianmichael and [Focus](https://github.com/flokosiol/kirby-focus) by @flokosiol**
* **Powered by [PhotoSwipe](https://github.com/dimsemenov/PhotoSwipe/releases) by @dimsemenov**
* **Parses `title` & `caption` image-fields directly into PhotoSwipe**
* **Configurable & Customizable to it's core 🤘**

![Demo of Kirby Lightbox-Gallery](demo.gif)


# 🤹‍ Demos

* [wottpal.com](http://wottpal.com/items/kirby-lightbox-gallery) (my personal site)
* **[Submit yours](https://twitter.com/wottpal)**


# 🤸 Installation
Because of the PhotoSwipe dependency multiple steps are necessary for a proper installation of the plugin. Also **PHP 5.6+** is required. If you have any questions feel free to [reach out](https://twitter.com/wottpal) or file an issue.



### 1. Install the plugin itself
*Recommended*: Use [Kirby's CLI](https://github.com/getkirby/cli) and install the plugin via: `kirby plugin:install wottpal/kirby-lightbox-gallery`


Oldschool: Download this repo and move the folder to `site/plugins/`.


### 2. Download Photoswipe
Download [PhotoSwipe](https://github.com/dimsemenov/PhotoSwipe/releases) (tested version 4.1.3) rename the `dist` directory to `photoswipe` and place it under `assets/vendor/`.

Note: Additionally you should minify the two `.css` files of PhotoSwipe which is weirdly not done by them. I love to use [Squeezer](https://squeezerapp.com/) for macOS for that. In the next section I'll assume that minified versions of these files exist.


### 3. Link Resources

Embed necessary styles within your `<head>`:

```php
<?= css([
  'media/plugins/wottpal/lightbox-gallery/gallery.min.css',
  'assets/vendor/photoswipe/photoswipe.min.css',
  'assets/vendor/photoswipe/default-skin/default-skin.min.css'
  ]) ?>
```

Embed necessary scripts before the end of your `</body>`:

```php
<?= js([
  'assets/vendor/photoswipe/photoswipe.min.js',
  'assets/vendor/photoswipe/photoswipe-ui-default.min.js',
  'media/plugins/wottpal/lightbox-gallery/init-photoswipe.min.js'
]) ?>
```

If you use the default initialization (`init-photoswipe.min.js`) and want to support older browsers you may need to add polyfills for the `forEach` and `Array.from` functions.


#### Customization
Except the PhotoSwipe Base CSS & JS you can literally replace every dependency with your own code. A good start for this is to copy & paste the original dependency (e.g. `'site/plugins/lightbox-gallery/src_assets/init-photoswipe.js'`) into your own assets folder, do your modifications and change the asset-paths accordingly. But be aware that the plugin changes over time and you may have to keep your changes compatible.


# 🏊 Usage Examples

```bash
(gallery: all)  # Includes all images of the page
(gallery: 1.png 2.png 3.png)  # Includes only selected images
(gallery: all page: projects/a)  # Includes all images of the page with the given uri

(gallery: all limit: 2)  # Only shows thumbs of the first two images

(gallery: all order: random)  # Shuffles all images
(gallery: all order: reverse)  # Displays all images in reverse order

(gallery: all cols: 2 4)  # Sets cols to ['min' => 2, 'max' => 4]
(gallery: all cols: 3)  # Sets cols to ['min' => 3, 'max' => 3]
(gallery: all mobilecols: 1 2)  # Sets mobilecols to ['min' => 1, 'max' => 2]

(gallery: all stretch: last)  # Stretch trailing items instead of first
(gallery: all stretch: none)  # Disable stretching

(gallery: all class: my-class id: my-id)  # Applied a class & id to the gallery-wrapper
```

Please see the following section for further description how the particular parameters work. Options set in a Kirbytag *always* overwrite global options.


# 🤺 Options

The following options can be set globally in your `config.php` with `'wottpal.lightboxgallery.KEY' => 'VALUE'`. You can also set multiple keys with the example below. 🤓
```bash
'wottpal.lightboxgallery' => [
  'KEY' => 'VALUE'
]
```


*****

* `wottpal.lightboxgallery.kirbytext.tagname` (default: `'gallery'`)

*The name of the tag to use this gallery within kirbytext. Like `(gallery: ...)`.*


*****

* `wottpal.lightboxgallery.id` (default: `''`)
* `wottpal.lightboxgallery.class` (default: `''`)

*Specify an ID or class which is added to the gallery-wrapper element. Use it for custom styling.*

*****

* `wottpal.lightboxgallery.combine` (default: `false`)

*If set to `true` all galleries on one page will be virtually the same. So you can navigate between all images in the same lightbox.*

*****

* `wottpal.lightboxgallery.limit` (default: `false`)

*Maximum amount of previewed thumbnails to be shown.*


Note: The hidden items are only *visually hidden* with CSS. By appending a `data-not-previewed` attribute to the `<figure>` elements.


*****

* `wottpal.lightboxgallery.cols` (default: `['min' => 3, 'max' => 4]`)
* `wottpal.lightboxgallery.mobilecols` (default: `['min' => 2, 'max' => 2]`)

*Defines the range of possible columns the gallery can choose of. Within this range an algorithm 🔮 tries to find the best match in terms of minimizing row-count and overhang with the given amount of thumbs.*

Note 1: The actual implementation is located under `helpers.php`.

Note 2: The mobile-breakpoint and gutter-width are currently defined in `src_assets/gallery.scss`. You can overwrite it by setting a `lightboxgallery.class` and adding your own styles or completely substituting the plugins CSS with your own stylesheets.


*****

* `wottpal.lightboxgallery.stretch` (default: `'first'`)

*If there are not enough images to fill all rows with as many items as columns by default the images in the first row are stretched to fill up the whole width. You can set this option to `'last'` to stretch all trailing items instead or set it to `'none'` to disable stretching at all.


*****

* `wottpal.lightboxgallery.thumb.provider` (default: `'thumb'`)

*Choose from one of the following providers for thumb-creation:*
  * `'thumb'` ([Kirby's built-in thumbnail class](https://getkirby.com/docs/templates/thumbnails))
  * `'focus'` ([Kirby-Focus](https://github.com/flokosiol/kirby-focus) by [@flokosiol](https://twitter.com/flokosiol))
  * `'imageset'` ([ImageSet](https://github.com/fabianmichael/kirby-imageset))
  * `false` (just use the image as thumb)

Note: Because thumbnail-creation for a whole gallery may take some time and you don't want the first visitor of your site to suffer I recommend you to have a look into [ImageKit](https://github.com/fabianmichael/kirby-imagekit).

*****

* `wottpal.lightboxgallery.thumb.options` (default: `["width" => 800, "height" => 800, "crop" => true]`)

*Define the options for the selected `thumb.provider`. Works exactly the same way as you would use the options with their respective functions. If you set the `thumb.provider` to something else than `'thumb'` or `false` you **must** set this option.*

Note 1: The default-value is crops 800x800 thumbnails with Kirby's built-in thumbnail class.

Note 2: If you use `focus` put all values in an array. So for example if you set it to `[300,300]` the plugin will call `focusCrop(300,300)`.

Note 3: If you use `imageset` you set this option to something like `['400x400-1000x1000, 4']` to generate responsive square images. (You should specify `sizes` as well).

*****


# 🏄 Changelog

Have a look at the [releases page](https://github.com/wottpal/kirby-lightbox-gallery/releases).


# 🏋️ Roadmap
**Contributions welcome 😘**

- [x] Possibility to define thumb-sizes for the Non-ImageSet version
- [x] Option to move stretched row to the end (only if `count % col != 0`)
- [x] Option to not stretch row and keep the same column-count (only if `count % col != 0`).
- [x] Allow cols/mobilecols to be set in the kirbytag
- [ ] Think more about non-square images
- [ ] Dynamic thumb-options based on column-count
- [ ] Define responsive image-sources for PhotoSwipe
- [ ] Option to disable lightbox usage
- [ ] Enable use of History-API of PhotoSwipe in init-photoswipe.js
- [ ] Maybe a new breakpoint-based API for defining min/max cols like
- [ ] Support for videos (esp. `webM` with `mp4` and `gif` fallbacks)


# 👨‍💻 Development
For minification and transpilation I use [Gulp](http://gulpjs.com). Please note that all files under `assets/` are only the generated ones from `src_assets/`.

```bash
# Install Dependencies
npm i

# Or: Install Dependencies for Hipsters
yarn

# Build & Watch (Install 'gulp-cli' globally to omit the 'npx')
npx gulp
```

# 💰‍ Pricing
Just kidding. This plugin is totally free. Please consider following [me](https://twitter.com/wottpal) on Twitter if it saved your day.

[![Twitter Follow](https://img.shields.io/twitter/follow/wottpal.svg?style=social&label=Follow)](https://twitter.com/wottpal)

You can also check out one of [my other Kirby-plugins](https://wottpal.com/items/my-kirby-plugins):

* [HTML5-Video Kirbytag](https://github.com/wottpal/kirby-video) - Adds a kirbytag for embedding HTML5-videos with a variety of features.
* [Anchor-Headings](https://github.com/wottpal/kirby-anchor-headings) - A kirby field-method which enumerates heading-elements, generates IDs for anchor-links and inserts custom markup based on your needs.
