/**
 * This is the default-initialization of PhotoSwipe for the lightbox-gallery.
 * It features dynamic markup insertion, title/caption detection, the ability
 * to combine all gallery-elements on one page into one gallery and much more..
 *
 * If you want to customize it, just copy & paste it into your 'assets/'-folder
 * and replace the script-references before the end of your <body>.
 */


/**
* The markup for a PhotoSwipe-modal. Only necessary once on a page.
*/
const PHOTOSWIPE_MODAL_MARKUP = `
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Fullscreen-Mode"></button><button class="pswp__button pswp__button--zoom" title="Zoom"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (Arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (Arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>
`


/**
* This module initializes a PhotoSwipe-Gallery by parsing images and metadata
* for all galeries selectable via '[data-js="photoswipe-gallery"]'.
*/
const initPhotoswipe = (() => {

  /* Constants */
  const DATA_SIZE_SPLIT_CHAR = '×';


  /* Variables */
  let gallerySelector, globalFigureSelector
  let combineGalleries
  let galleryEls = []


  /* Private Functions */
  /**
  * This function parses slide data (url, title, size ...) from <figure> thumb- elements for a given gallery-element.
  */
  function parseThumbnailElements(galleryEl) {
    let thumbEls, items = []

    // Either select all items on the page or only those for one gallery
    if (combineGalleries) {
      thumbEls = Array.from(document.querySelectorAll(globalFigureSelector))
    } else {
      thumbEls = Array.from(galleryEl.querySelectorAll('figure'))
    }

    // Include only element nodes of type figure
    thumbEls.filter(thumb => {
      return thumb.nodeType !== 1 || (thumb.tagName && thumb.tagName.toUpperCase() !== 'FIGURE')
    })

    // Create a slide-object from each thumbs data
    thumbEls.forEach(thumbEl => {
      const linkEl = thumbEl.querySelector('a')
      const figcaptionEl = thumbEl.querySelector('figcaption')
      const size = linkEl.getAttribute('data-size').split(DATA_SIZE_SPLIT_CHAR)
      const item = {
        el: thumbEl,
        src: linkEl.getAttribute('href'),
        msrc: linkEl.getAttribute('data-image'),
        title: transformCaptionHTML(figcaptionEl),
        w: parseInt(size[0], 10),
        h: parseInt(size[1], 10)
      }
      items.push(item)
    })

    return items
  }

  /**
  * This function gathers & transforms the innerHTML of <figcaption>.
  */
  function transformCaptionHTML(figcaptionEl) {
    if (!figcaptionEl || !figcaptionEl.innerHTML) return ""
    const tmpEl = figcaptionEl.cloneNode(true);

    // Transform Links
    // This is necessary because multiple wrapped <a>'s are forbidden in HTML.
    const linksToTransform = tmpEl.querySelectorAll('span[data-link]');
    linksToTransform.forEach(spanEl => {
      const link = spanEl.getAttribute('data-link').trim();
      if (!link || link == "") return;

      const newLinkEl = document.createElement('a')
      newLinkEl.href = link
      newLinkEl.target = "_blank"
      newLinkEl.textContent = spanEl.textContent
      spanEl.innerHTML = ""
      spanEl.appendChild(newLinkEl)
    })

    return tmpEl.innerHTML;
  }

  /**
  * This function returns the nearest parent element.
  */
  function closest(el, fn) {
    return el && (fn(el) ? el : closest(el.parentNode, fn));
  }

  /**
  * This function is triggered when the user clicks on a thumbnail.
  * @param e [The click-event]
  */
  function onThumbnailsClick(e) {
    e = e || window.event;
    e.preventDefault ? e.preventDefault() : e.returnValue = false;

    const eTarget = e.target || e.srcElement;

    // Find root element of slide, return if it can't be found
    const clickedListItem = closest(eTarget, el => {
      return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
    })
    if (!clickedListItem) return

    // Determine index of the item by reading it's data-attribute
    const itemIndex = parseInt(clickedListItem.getAttribute('data-idx'));
    if (itemIndex < 0) return

    // Open PhotoSwipe if valid index was found
    const clickedGallery = combineGalleries ? galleryEls[0] : clickedListItem.parentNode
    openPhotoSwipe(itemIndex, clickedGallery, true)
    return false
  };

  /**
  * This function actually opens the PhotoSwipe-Gallery.
  * TODO: Photoswipe could by opened directly by an URL
  *       (by parsing the gallery- & item-indices from the Hash)
  */
  var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
    const pswpElement = document.querySelector('.pswp')
    const items = parseThumbnailElements(galleryElement)
    // if (!index || !pswpElement || !items) return

    const options = {
      galleryUID: galleryElement.getAttribute('data-pswp-uid'),
      index: index,

      history: false,
      bgOpacity: .95,
      fullscreenEl: false,

      // Share Options
      shareButtons: [
          {id:'facebook', label:'Share on Facebook', url:'https://www.facebook.com/sharer/sharer.php?u={{url}}'},
          {id:'twitter', label:'Tweet', url:'https://twitter.com/intent/tweet?text={{text}}&url={{url}}'},
          {id:'pinterest', label:'Pin it', url:'http://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}'},
          {id:'download', label:'Download image', url:'{{raw_image_url}}', download:true}
      ],

      // See Options -> getThumbBoundsFn section of documentation for more info
      getThumbBoundsFn: function(index) {
        var thumbnail = items[index].el.getElementsByTagName('img')[0],
        pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
        rect = thumbnail.getBoundingClientRect();

        return {
          x: rect.left,
          y: rect.top + pageYScroll,
          w: rect.width
        };
      }
    }

    if (disableAnimation) {
      options.hideAnimationDuration = 0
      options.showAnimationDuration = 0
    }

    // Pass data to PhotoSwipe and initialize it
    const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
  };



  /* Public Functions */

  return {

    /**
    * Dynamically sets IDs and Indices on Gallery- and Media-Elements.
    * If 'combine' is true all figures are indexed as if they where in one gallery.
    */
    initialize : (selector, combine) => {
      gallerySelector = selector
      globalFigureSelector = gallerySelector + ' figure'
      combineGalleries = combine
      galleryEls = document.querySelectorAll(gallerySelector)

      let figureIdx = 0
      for (let i = 0, l = galleryEls.length; i < l; i++) {
        // Continue Index-Counting if galleries combined
        if (!combineGalleries) figureIdx = 0

        galleryEls[i].setAttribute('data-pswp-uid', i + 1);

        // Set indices of pictures per gallery
        const figureEls = galleryEls[i].querySelectorAll('figure')
        for (let j = 0; j < figureEls.length; j++) {
          figureEls[j].setAttribute('data-idx', figureIdx)
          figureEls[j].addEventListener('click', onThumbnailsClick)
          figureIdx++
        }
      }
    }

  }

})()



/**
* Insert the PhotoSwipe-Modal Markup and initialize all galleries on the page.
*/
document.addEventListener('DOMContentLoaded', event => {
  const selector = '[data-js="photoswipe-gallery"]'
  const galleries = document.querySelectorAll(selector)
  if (!galleries || galleries.length <= 0) return

  // Insert Modal-Markup
  const parser = new DOMParser()
  const modalEl = parser.parseFromString(PHOTOSWIPE_MODAL_MARKUP, "text/html")
  document.body.appendChild(modalEl.body.firstChild)

  // Initialize Galleries
  const combineGalleries = galleries[0].getAttribute('combine-galleries') == 'true';
  initPhotoswipe.initialize(selector, combineGalleries)
})
