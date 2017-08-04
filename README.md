# 🖼 Kirby Lightbox-Gallery by [@wottpal](https://twitter.com/wottpal)

<!-- Buttons -->
![Release](https://img.shields.io/github/release/wottpal/kirby-lightbox-gallery/all.svg)
[![MIT](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/wottpal/kirby-lightbox-gallery/master/LICENSE)
[![Tweet](https://img.shields.io/twitter/url/https/github.com/wottpal/kirby-lightbox-gallery.svg?style=social)](https://twitter.com/intent/tweet?text=&#x1F5BC;&#x20;&#x4C;&#x69;&#x67;&#x68;&#x74;&#x62;&#x6F;&#x78;&#x2D;&#x47;&#x61;&#x6C;&#x6C;&#x65;&#x72;&#x79;&#x20;&#x66;&#x6F;&#x72;&#x20;&#x40;&#x67;&#x65;&#x74;&#x6B;&#x69;&#x72;&#x62;&#x79;&#x20;&#x62;&#x79;&#x20;&#x40;&#x77;&#x6F;&#x74;&#x74;&#x70;&#x61;&#x6C;&url=https://git.io/v7ajs)


(_Disclaimer:_ This is a pre-release.)

A plugin for the awesome [Kirby CMS](http://getkirby.com) which allows the editor to easily inline beautiful galleries with lightbox-support.

* **Dynamic & responsive Grid-Alignment**
* **Works with Kirby's built-in thumbnail class, [ImageSet](https://github.com/fabianmichael/kirby-imageset) by @fabianmichael and [Focus](https://github.com/flokosiol/kirby-focus) by @flokosiol**
* **Powered by [PhotoSwipe](https://github.com/dimsemenov/PhotoSwipe/releases) by @dimsemenov**
* **Parses `title` & `caption` image-fields directly into PhotoSwipe**
* **Configurable & Customizable to it's core 🤘**


# 🤹‍ Demos

*Coming Soon* ([submit your demo](https://twitter.com/wottpal))

In the meantime you can watch this very satisfying GIF.

![Demo of Kirby Lightbox-Gallery](demo.gif)


# 🤸 Installation
Because of the PhotoSwipe dependency multiple steps are necessary for a proper installation of the plugin. Also **PHP 5.6+** is required. If you have any questions feel free to [reach out](https://twitter.com/wottpal) or file an issue.



### 1. Install the plugin itself
*Recommended*: Use [Kirby's CLI](https://github.com/getkirby/cli) and install the plugin via: `kirby plugin:install wottpal/kirby-lightbox-gallery`


Oldschool: Download this repo and move the folder to `site/plugins/`.


### 2. Download Photoswipe
Download [PhotoSwipe](https://github.com/dimsemenov/PhotoSwipe/releases) (tested version 4.1.2) rename the `dist` directory to `photoswipe` and place it under `assets/vendor/`.

Note: Additionally you should minify the two `.css` files of PhotoSwipe which is weirdly not done by them. I love to use [Squeezer](https://squeezerapp.com/) for macOS for that. In the next section I'll assume that minified versions of these files exist.


### 3. Link Resources

Embed necessary styles within your `<head>`:

```
<?= css([
  'assets/plugins/lightbox-gallery/gallery.min.css',
  'assets/vendor/photoswipe/photoswipe.min.css',
  'assets/vendor/photoswipe/default-skin/default-skin.min.css'
  ]) ?>
```

Embed necessary scripts before the end of your `</body>`:

```
<?= js([
  'assets/vendor/photoswipe/photoswipe.min.js',
  'assets/vendor/photoswipe/photoswipe-ui-default.min.js',
  'assets/plugins/lightbox-gallery/init-photoswipe.min.js'
]) ?>
```

#### Customization
Except the PhotoSwipe Base CSS & JS you can literally replace every dependency with your own code. A good start for this is to copy & paste the original dependency (e.g. `'site/plugins/lightbox-gallery/src_assets/init-photoswipe.js'`) into your own assets folder, do your modifications and change the asset-paths accordingly. But be aware that the plugin changes over time and you may have to keep your changes compatible.


# 🏊 Usage

```
(gallery: 1.png 2.png 3.png 4.png)
(gallery: 1.png 2.png 3.png 4.png limit: 2)
```


# 🤺 Options

The following options can be set globally in your `config.php` with `c::set($key, $value = null)`. You can also set multiple keys with `c::set([$key => $value, ..])`. 🤓

*****

* `lightboxgallery.kirbytext.tagname` (default: `'gallery'`)

*The name of the tag to use this gallery within kirbytext. Like `(gallery: ...)`.*


*****

* `lightboxgallery.class` (default: `''`)

*Additional class which is added to all gallery-elements. Use it for custom styling.*

*****

* `lightboxgallery.combine` (default: `false`)

*If set to `true` all galleries on one page will be virtually the same. So you can navigate between all images in the same lightbox.*

*****

* `lightboxgallery.limit` (default: `false`)

*Maximum amount of previewed thumbnails to be shown.*


Note: The hidden items are only *visually hidden* with CSS. By appending a `data-not-previewed` attribute to the `<figure>` elements.


*****

* `lightboxgallery.cols` (default: `['min' => 3, 'max' => 4]`)
* `lightboxgallery.mobilecols` (default: `['min' => 2, 'max' => 2]`)

*Defines the range of possible columns the gallery can choose of. Within this range an algorithm 🔮 tries to find the best match in terms of minimizing row-count and overhang with the given amount of thumbs.*

Note 1: The actual implementation is located under `helpers.php`.

Note 2: The mobile-breakpoint and gutter-width are currently defined in `src_assets/gallery.scss`. You can overwrite it by setting a `lightboxgallery.class` and adding your own styles or completely substituting the plugins CSS with your own stylesheets.


*****

* `lightboxgallery.stretch` (default: `true`)
* `lightboxgallery.stretch.last` (default: `false`)

*If there are not enough images to fill all rows with as many items as columns by default the images in the first row are stretched to fill up the whole width. You can move this stretched row to the end if you set `stretch.last` to `true` or completely disable this behavior by setting `stretch` to `false`.*

Note: If you set `stretch` to `false` the value for `stretch.last` is ignored and the not-filled row is always at the end.


*****

* `lightboxgallery.thumb.provider` (default: `'thumb'`)

*Choose from one of the following providers for thumb-creation:*
  * `'thumb'` ([Kirby's built-in thumbnail class](https://getkirby.com/docs/templates/thumbnails))
  * `'focus'` ([Kirby-Focus](https://github.com/flokosiol/kirby-focus) by [@flokosiol](https://twitter.com/flokosiol))
  * `'imageset'` ([ImageSet](https://github.com/fabianmichael/kirby-imageset))
  * `false` (just use the image as thumb)

Note: Because thumbnail-creation for a whole gallery may take some time and you don't want the first visitor of your site to suffer I recommend you to have a look into [ImageKit](https://github.com/fabianmichael/kirby-imagekit).

*****

* `lightboxgallery.thumb.options` (default: `["width" => 800, "height" => 800, "crop" => true]`)

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
- [ ] Think more about non-square images
- [ ] Dynamic thumb-options based on column-count
- [ ] Define responsive image-sources for PhotoSwipe
- [ ] Option to disable lightbox usage
- [ ] Enable use of History-API of PhotoSwipe in init-photoswipe.js
- [ ] Allow cols/mobilecols to be set in the kirbytag
- [ ] Maybe a new breakpoint-based API for defining min/max cols like
- [ ] Support for videos (esp. `webM` with `mp4` and `gif` fallbacks)


# 👨‍💻 Development
For minification and transpilation I use [Gulp](http://gulpjs.com). Please note that all files under `assets/` are only the generated ones from `src_assets/`.

```
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

You can also check out one of my other Kirby-plugins:

* [HTML5-Video Kirbytag](https://github.com/wottpal/kirby-video) - Adds a kirbytag for embedding HTML5-videos with a variety of features.
* [Anchor-Headings](https://github.com/wottpal/kirby-anchor-headings) - A kirby field-method which enumerates heading-elements, generates IDs for anchor-links and inserts custom markup based on your needs.
