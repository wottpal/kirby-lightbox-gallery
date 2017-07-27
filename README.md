# Lightbox-Gallery for Kirby
A plugin for the awesome [Kirby CMS](http://getkirby.com) which allows the editor to easily inline beautiful galleries with lightbox-support. By [@wottpal](https://twitter.com/wottpal).

* **Dynamic & responsive Grid-Alignment**
* **Works with responsive & lazy-loading images by supporting [ImageSet](https://github.com/fabianmichael/kirby-imageset) & [ImageKit](https://github.com/fabianmichael/kirby-imagekit) by @fabianmichael**
* **Powered by [PhotoSwipe](https://github.com/dimsemenov/PhotoSwipe/releases) by @dimsemenov**
* **Configurable & Customizable to it's core 🤘**


## 🤹‍ Demos

*Coming Soon* ([submit your demo](https://twitter.com/wottpal))

In the meantime you can watch this very satisfying GIF.

![Demo of Kirby Lightbox-Gallery](demo.gif)


# 🤸 Installation
Because of the PhotoSwipe dependency multiple steps are necessary for a proper installation of the plugin. If you have any questions feel free to [reach out](https://twitter.com/wottpal) or file an issue.

### 1. Install the plugin itself
*Recommended*: Use [Kirby's CLI](https://github.com/getkirby/cli) and install the plugin via:

`kirby plugin:install wottpal/kirby-lightbox-gallery`


<small>
Oldschool: Download this repo and move the folder to `site/plugins/`.
</small>

### 2. Download Photoswipe
Download [PhotoSwipe](https://github.com/dimsemenov/PhotoSwipe/releases) (tested version 4.1.2) rename the `dist` directory to `photoswipe` and place it under `assets/vendor/`.

<small>
Note: Additionally you should minify the two `.css` files of PhotoSwipe which is weirdly not done by them. I love to use [Squeezer](https://squeezerapp.com) (macOS) for that. In the next section I'll assume that minified versions of these files exist.
</small>

### 2. Link Resources

Embed necessary styles within your `<head>`:

```
<?= css([
  'assets/plugins/lightbox-gallery/gallery.min.scss',
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

### Customization
Except the PhotoSwipe Base CSS & JS you can literally replace every dependency with your own code. A good start for this is to copy & paste the original dependency (e.g. `'site/plugins/lightbox-gallery/src_assets/init-photoswipe.js'`) into your own assets folder, do your modifications and change the asset-paths accordingly. But be aware that the plugin changes over time and you may have to keep your changes compatible.


# 🏊 Usage

```
(gallery: 1.png 2.png 3.png 4.png)
(gallery: 1.png 2.png 3.png 4.png max: 2)
```


# 🤺 Options
The following options can be set globally in your `config.php` with `c::set($key, $value = null)`. You can also set multiple keys with `c::set([$key => $value, ..])`. 🤓

*****

* `lightboxgallery.kirbytext.tagname` (default: `'gallery'`)

*The name of the tag to use this gallery within kirbytext. Like `(gallery: ...)`.*

*****

* `lightboxgallery.imageset` (default: `false`)

*Set this to `true` **and** define a `thumb.preset` (next option) to enable [ImageSet](https://github.com/fabianmichael/kirby-imageset)-support*


*****

* `lightboxgallery.imageset.thumb.preset` (no default)

*The preset which is used to generate the gallery-thumbs. See [ImageSet](https://github.com/fabianmichael/kirby-imageset)-documentation for more infos.*

<small>
Note: I use `'400x400-1000x1000, 4'` to generate responsive square images.
</small>

*****

* `lightboxgallery.class` (default: `''`)

*Additional class which is added to all gallery-elements. Use it for custom styling.*

*****

* `lightboxgallery.combine` (default: `false`)

*If set to `true` all galleries on one page will be virtually the same. So you can navigate between all images in the same lightbox.*

*****

* `lightboxgallery.max.preview` (default: `false`)

*Maximum amount of previewed images to be shown.*

<small>
Note: The hidden items are only *visually hidden* with CSS. By appending a `data-not-previewed` attribute to the `<figure>` elements.
</small>

*****

* `lightboxgallery.cols` (default: `['min' => 3, 'max' => 4]`)
* `lightboxgallery.mobilecols` (default: `['min' => 2, 'max' => 2]`)

Defines the range of possible columns the gallery can choose of. Within this range an algorithm 🔮 tries to find the best match in terms of minimizing row-count and overhang with the given amount of thumbs.


<small>
Note 1: The actual implementation is located under `helpers.php`.
</small>

<small>
Note 2: The mobile-breakpoint and gutter-width are currently defined in `src_assets/gallery.scss`. You can overwrite it by setting a `lightboxgallery.class` and adding your own styles or completely substituting the plugins CSS with your own stylesheets.
</small>


*****


# 🏄 Changelog

#### 0.1.0
Initial Release


# 🏋️ Roadmap
**Contributions welcome 😘**

- [ ] Think more about non-square images
- [ ] Dynamic ImageSet Preset based on column-count
- [ ] Possibility to define thumb-sizes for the Non-ImageSet version
- [ ] Define responsive PhotoSwipe image-sources
- [ ] Option to move stretched row to the end (only if `count % col != 0`)
- [ ] Option to not stretch row and keep the same column-count (only if `count % col != 0`). But I think this only makes visually sense if it's moved to the end with the option before.
- [ ] Option to disable lightbox usage
- [ ] Enable use of History-API of PhotoSwipe in init-photoswipe.js
- [ ] Allow cols/mobilecols to be set in the kirbytag
- [ ] Maybe a new breakpoint-based API for defining min/max cols like
- [ ] Support for videos (esp. `webM` with `mp4` and `gif` fallbacks)
- [ ] **Squashing Bugs**


# 👨‍💻 Development
For minification and transpilation I use [Gulp](http://gulpjs.com). Please note that all files under `assets/` are only the generated ones from `src_assets/`.

```
# Install Dependencies
npm i

# Or: Install Dependencies for Hipsters
yarn

# Build & Watch
gulp
```

# 💰‍ Pricing
Just kidding. This plugin is totally free. Please consider following [me](https://twitter.com/wottpal) on Twitter if it made your day.
