document.addEventListener('DOMContentLoaded', function () {
  function enableSmartNavigationPrefetch() {
    const supportsPrefetch = (function () {
      const link = document.createElement('link');
      return !!(link.relList && link.relList.supports && link.relList.supports('prefetch'));
    })();

    if (!supportsPrefetch) return;

    const prefetched = new Set();
    const shouldPrefetch = function (anchor) {
      if (!anchor || !anchor.href) return false;
      if (anchor.target && anchor.target !== '_self') return false;
      if (anchor.hasAttribute('download')) return false;
      if ((anchor.getAttribute('rel') || '').indexOf('nofollow') !== -1) return false;

      let url;
      try {
        url = new URL(anchor.href, window.location.origin);
      } catch (error) {
        return false;
      }

      if (url.origin !== window.location.origin) return false;
      if (url.pathname === window.location.pathname && url.search === window.location.search) return false;
      if (url.hash && url.pathname === window.location.pathname) return false;

      return true;
    };

    const prefetchHref = function (href) {
      if (!href || prefetched.has(href)) return;
      prefetched.add(href);

      const link = document.createElement('link');
      link.rel = 'prefetch';
      link.as = 'document';
      link.href = href;
      document.head.appendChild(link);
    };

    const onIntent = function (event) {
      const anchor = event.target && event.target.closest ? event.target.closest('a[href]') : null;
      if (!shouldPrefetch(anchor)) return;
      prefetchHref(anchor.href);
    };

    document.addEventListener('mouseover', onIntent, { passive: true });
    document.addEventListener('touchstart', onIntent, { passive: true });
    document.addEventListener('focusin', onIntent);
  }

  function optimizeDeferredMedia() {
    const images = document.querySelectorAll('img');
    if (!images.length) return;

    const viewportHeight = window.innerHeight || 900;
    images.forEach(function (img) {
      if (!img) return;
      if (!img.hasAttribute('loading')) {
        const rect = img.getBoundingClientRect();
        img.loading = rect.top > viewportHeight * 1.2 ? 'lazy' : 'eager';
      }
      if (!img.hasAttribute('decoding')) {
        img.decoding = 'async';
      }
    });
  }

  enableSmartNavigationPrefetch();
  optimizeDeferredMedia();

  function normalizeGoogleMapsKey(value) {
    const raw = String(value || '').trim();
    if (!raw) return '';

    const keyPattern = /(AIza[0-9A-Za-z_-]{20,})/;
    const directMatch = raw.match(keyPattern);
    if (directMatch && directMatch[1]) return directMatch[1];

    const normalizeNested = function (candidate) {
      const nestedRaw = String(candidate || '').trim();
      if (!nestedRaw || nestedRaw === raw) return '';

      const nestedMatch = nestedRaw.match(keyPattern);
      if (nestedMatch && nestedMatch[1]) return nestedMatch[1];

      try {
        const nestedUrl = new URL(decodeURIComponent(nestedRaw));
        const nestedKey = nestedUrl.searchParams.get('key');
        if (nestedKey) {
          const nestedKeyMatch = String(nestedKey).match(keyPattern);
          if (nestedKeyMatch && nestedKeyMatch[1]) return nestedKeyMatch[1];
        }
      } catch (error) {
        return '';
      }

      return '';
    };

    try {
      const url = new URL(raw);
      const fromQuery = url.searchParams.get('key');
      if (fromQuery) {
        const fromQueryTrim = fromQuery.trim();
        const fromQueryMatch = fromQueryTrim.match(keyPattern);
        if (fromQueryMatch && fromQueryMatch[1]) return fromQueryMatch[1];

        const nested = normalizeNested(fromQueryTrim);
        if (nested) return nested;
      }
    } catch (error) {
      const fromKeyParam = raw.match(/[?&]key=([^&]+)/);
      if (fromKeyParam && fromKeyParam[1]) {
        const decoded = decodeURIComponent(fromKeyParam[1]).trim();
        const decodedMatch = decoded.match(keyPattern);
        if (decodedMatch && decodedMatch[1]) return decodedMatch[1];

        const nested = normalizeNested(decoded);
        if (nested) return nested;
      }
    }

    return '';
  }

  function renderMapFallback() {
    const mapTarget = document.querySelector('[data-goody-map]');
    if (!mapTarget) return;
    if (mapTarget.dataset.mapReady === '1') return;

    const address = mapTarget.getAttribute('data-address') || '';
    const markerTitle = mapTarget.getAttribute('data-title') || 'Location';
    const query = address || markerTitle;
    const iframe = document.createElement('iframe');
    iframe.loading = 'lazy';
    iframe.referrerPolicy = 'no-referrer-when-downgrade';
    iframe.src =
      'https://www.google.com/maps?q=' +
      encodeURIComponent(query) +
      '&output=embed';
    iframe.style.width = '100%';
    iframe.style.minHeight = '340px';
    iframe.style.border = '0';

    mapTarget.innerHTML = '';
    mapTarget.appendChild(iframe);
    mapTarget.dataset.mapReady = '1';
  }

  function loadGoogleMapsScriptIfNeeded() {
    const mapTarget = document.querySelector('[data-goody-map]');
    const apiKey = normalizeGoogleMapsKey(window.goodyTheme && goodyTheme.mapsApiKey ? goodyTheme.mapsApiKey : '');

    if (!mapTarget || !apiKey) return;
    if (window.google && window.google.maps) return;

    const existing = document.querySelector('script[src*="maps.googleapis.com/maps/api/js"]');
    if (existing) return;

    const script = document.createElement('script');
    script.src =
      'https://maps.googleapis.com/maps/api/js?key=' +
      encodeURIComponent(apiKey) +
      '&libraries=places&callback=initMap&loading=async';
    script.onerror = function () {
      renderMapFallback();
    };
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);

    setTimeout(function () {
      if (!window.goodyMapReady && !(window.google && window.google.maps)) {
        renderMapFallback();
      }
    }, 3200);
  }

  function renderGoodyMap() {
    const mapTarget = document.querySelector('[data-goody-map]');
    if (!mapTarget) return;
    if (mapTarget.dataset.mapReady === '1') return;
    if (!window.google || !google.maps) return;

    const address = mapTarget.getAttribute('data-address') || '';
    const markerTitle = mapTarget.getAttribute('data-title') || 'Location';
    const fallbackCenter = { lat: 23.8103, lng: 90.4125 };
    const parseLatLng = function (value) {
      const raw = String(value || '').trim();
      if (!raw) return null;

      const match = raw.match(/^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)\s*$/);
      if (!match) return null;

      const lat = parseFloat(match[1]);
      const lng = parseFloat(match[2]);
      if (Number.isNaN(lat) || Number.isNaN(lng)) return null;
      if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return null;

      return { lat: lat, lng: lng };
    };
    const coordinateCenter = parseLatLng(address);

    // Avoid Geocoding API dependency (prevents "Geocoding Service not activated" errors).
    // If address is text-only, use iframe fallback map which does not require Geocoding API.
    if (address && !coordinateCenter) {
      renderMapFallback();
      return;
    }
    const centerPoint = coordinateCenter || fallbackCenter;

    let map;
    let marker;

    try {
      const markerIcon = google.maps.SymbolPath
        ? {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 7,
            fillColor: '#8ee37b',
            fillOpacity: 1,
            strokeColor: '#153c28',
            strokeWeight: 2
          }
        : null;

      map = new google.maps.Map(mapTarget, {
        center: centerPoint,
        zoom: 15
      });

      marker = new google.maps.Marker({
        map: map,
        position: centerPoint,
        title: markerTitle,
        icon: markerIcon || undefined
      });
    } catch (error) {
      renderMapFallback();
      return;
    }

    mapTarget.dataset.mapReady = '1';
  }

  window.initMap = function () {
    window.goodyMapReady = true;
    renderGoodyMap();
  };

  window.gm_authFailure = function () {
    renderMapFallback();
  };

  window.addEventListener('goodyMapReady', renderGoodyMap);
  if (window.goodyMapReady || (window.google && window.google.maps)) {
    renderGoodyMap();
  }

  const mapTarget = document.querySelector('[data-goody-map]');
  if (mapTarget) {
    if ('IntersectionObserver' in window) {
      const mapObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          mapObserver.disconnect();
          loadGoogleMapsScriptIfNeeded();
        });
      }, { rootMargin: '240px 0px' });

      mapObserver.observe(mapTarget);
    } else {
      loadGoogleMapsScriptIfNeeded();
    }
  }

  const trackingBoxes = Array.prototype.slice.call(document.querySelectorAll('[data-goody-tracking-box]'));
  if (trackingBoxes.length && window.goodyTheme && goodyTheme.ajaxUrl) {
    const trackingKeyParamKeys = ['key', 'order_key', 'wc_order_key'];
    const trackingStageOrder = ['requested', 'confirmed', 'preparing', 'ready', 'with_delivery_provider', 'completed'];

    const normalizeTrackingStage = function (value) {
      const raw = String(value || '').trim().toLowerCase().replace(/[-\s]+/g, '_');
      const aliases = {
        requested: 'requested',
        accepted: 'requested',
        order_received: 'requested',
        received: 'requested',
        pending: 'requested',
        on_hold: 'requested',
        draft: 'requested',
        checkout_draft: 'requested',
        failed: 'requested',
        cancelled: 'requested',
        refunded: 'requested',
        confirmed: 'confirmed',
        picked: 'confirmed',
        pickup: 'confirmed',
        picked_up: 'confirmed',
        assigned: 'confirmed',
        courier_assigned: 'confirmed',
        preparing: 'preparing',
        in_transit: 'preparing',
        transit: 'preparing',
        on_the_way: 'preparing',
        shipping: 'preparing',
        processing: 'preparing',
        shipped: 'preparing',
        ready_for_delivery: 'ready',
        out_for_delivery: 'ready',
        ready: 'ready',
        with_delivery_provider: 'with_delivery_provider',
        delivery_provider: 'with_delivery_provider',
        provider: 'with_delivery_provider',
        delivered: 'completed',
        complete: 'completed',
        completed: 'completed'
      };

      return aliases[raw] || '';
    };

    const trackingStageLabelMap = {
      requested: 'Requested',
      confirmed: 'Confirmed',
      preparing: 'Preparing',
      ready: 'Ready',
      with_delivery_provider: 'Delivery Provider',
      completed: 'Completed'
    };

    const detectTrackingStage = function (state) {
      if (!state || typeof state !== 'object') return '';

      let stage = normalizeTrackingStage(state.stage || '');
      if (stage) return stage;

      stage = normalizeTrackingStage(state.status || '');
      if (stage) return stage;

      const timeline = Array.isArray(state.timeline) ? state.timeline : [];
      for (let index = timeline.length - 1; index >= 0; index -= 1) {
        const event = timeline[index] || {};
        stage = normalizeTrackingStage(event.stage || event.status || event.title || '');
        if (stage) return stage;
      }

      const text = String(state.status || state.message || '').toLowerCase();
      if (text.indexOf('deliver') !== -1) return text.indexOf('provider') !== -1 ? 'with_delivery_provider' : 'completed';
      if (text.indexOf('ready') !== -1 || text.indexOf('out for delivery') !== -1) return 'ready';
      if (text.indexOf('transit') !== -1 || text.indexOf('way') !== -1 || text.indexOf('ship') !== -1) return 'preparing';
      if (text.indexOf('pick') !== -1 || text.indexOf('assign') !== -1 || text.indexOf('courier') !== -1) return 'confirmed';
      if (text.indexOf('confirm') !== -1) return 'confirmed';
      if (text.indexOf('accept') !== -1 || text.indexOf('receiv') !== -1 || text.indexOf('new order') !== -1) return 'requested';
      if (text.indexOf('complete') !== -1 || text.indexOf('served') !== -1 || text.indexOf('finished') !== -1) return 'completed';

      return '';
    };

    const updateTextNode = function (scope, selector, value) {
      const element = scope.querySelector(selector);
      const text = String(value || '').trim();
      if (element && text) {
        element.textContent = text;
      }
    };

    const updateDeliveryDetails = function (scope, state) {
      const delivery = scope.querySelector('[data-tracking-delivery-value]');
      if (!delivery) return;

      const normalizedStage = normalizeTrackingStage(state.stage || '');
      const stageLabel = normalizedStage && trackingStageLabelMap[normalizedStage]
        ? trackingStageLabelMap[normalizedStage]
        : String(state.stage || '').trim();

      const fragments = [
        String(state.provider || '').trim(),
        String(stageLabel || '').trim(),
        String(state.eta || '').trim()
      ].filter(Boolean);

      if (!fragments.length) {
        delivery.innerHTML = '';
        return;
      }

      delivery.innerHTML = '';
      fragments.forEach(function (fragment, index) {
        const element = document.createElement(index === 0 ? 'strong' : 'span');
        element.textContent = fragment;
        delivery.appendChild(element);
      });
    };

    const updateTrackingSteps = function (scope, state) {
      const currentStage = detectTrackingStage(state);
      if (!currentStage) return;

      const currentIndex = trackingStageOrder.indexOf(currentStage);
      if (currentIndex < 0) return;

      scope.querySelectorAll('[data-tracking-step]').forEach(function (step) {
        const stepIndex = trackingStageOrder.indexOf(String(step.getAttribute('data-tracking-step') || ''));
        if (stepIndex < 0) return;

        step.classList.toggle('is-done', stepIndex <= currentIndex);
        step.classList.toggle('is-active', stepIndex === currentIndex);
      });
    };

    const updateTrackingTimeline = function (scope, state) {
      const timeline = scope.querySelector('[data-goody-tracking-timeline]');
      const events = Array.isArray(state.timeline) ? state.timeline : [];
      if (!timeline || !events.length) return;

      timeline.innerHTML = '';
      events.forEach(function (event) {
        const title = String(event.title || event.status || event.label || '').trim();
        const description = String(event.description || event.message || event.note || '').trim();
        const time = String(event.time || event.timestamp || event.date || '').trim();

        if (!title && !description && !time) return;

        const article = document.createElement('article');
        article.className = 'tracking-event' + (event.completed === false ? '' : ' is-done');

        const dot = document.createElement('span');
        dot.className = 'tracking-event__dot';
        dot.setAttribute('aria-hidden', 'true');
        article.appendChild(dot);

        const content = document.createElement('div');
        content.className = 'tracking-event__content';

        if (title) {
          const heading = document.createElement('h4');
          heading.textContent = title;
          content.appendChild(heading);
        }

        if (description) {
          const paragraph = document.createElement('p');
          paragraph.textContent = description;
          content.appendChild(paragraph);
        }

        if (time) {
          const timeElement = document.createElement('time');
          timeElement.textContent = time;
          content.appendChild(timeElement);
        }

        article.appendChild(content);
        timeline.appendChild(article);
      });
    };

    const resolveTrackingOrderKey = function () {
      try {
        const params = new URLSearchParams(window.location.search || '');
        for (let i = 0; i < trackingKeyParamKeys.length; i += 1) {
          const value = String(params.get(trackingKeyParamKeys[i]) || '').trim();
          if (value) return value;
        }
      } catch (error) {
        return '';
      }
      return '';
    };

    trackingBoxes.forEach(function (trackingBox) {
      const trackingText = trackingBox.querySelector('[data-goody-tracking-text]');
      const trackingLink = trackingBox.querySelector('[data-goody-tracking-link]');
      const baseTrackingText = String(trackingBox.getAttribute('data-tracking-base') || '').trim();
      const strictTrackingParams = trackingBox.getAttribute('data-tracking-strict') === '1';
      const trackingScope = trackingBox.closest('.goody-status-card, .tracking-shell, .reserve-zone') || trackingBox;
      const trackingOrderInput = trackingScope.querySelector('input[name="order_id"]');
      const trackingKeyInput = trackingScope.querySelector('input[name="key"], input[name="order_key"], input[name="wc_order_key"]');

      const resolveTrackingOrderId = function () {
        const localValue = String(trackingOrderInput && trackingOrderInput.value ? trackingOrderInput.value : '').trim();
        if (localValue) {
          return localValue;
        }

        const trackingOrderParamKeys = strictTrackingParams ? [
          'order_id',
          'track',
          'tracking_id',
          'order',
          'id',
          'external_order_id'
        ] : [
          'order_id',
          'track',
          'tracking_id',
          'order',
          'id',
          'external_order_id',
          'reference',
          'ref'
        ];

        try {
          const params = new URLSearchParams(window.location.search || '');
          for (let i = 0; i < trackingOrderParamKeys.length; i += 1) {
            const value = String(params.get(trackingOrderParamKeys[i]) || '').trim();
            if (value) return value;
          }

          const path = String(window.location.pathname || '');
          const match = path.match(/\/(?:order-received|view-order|order-pay)\/(\d+)/i);
          if (match && match[1]) {
            return String(match[1]).trim();
          }
        } catch (error) {
          return '';
        }
        return '';
      };

      const trackingOrderId = resolveTrackingOrderId();
      const trackingOrderKey = String(trackingKeyInput && trackingKeyInput.value ? trackingKeyInput.value : '').trim() || resolveTrackingOrderKey();
      const hasTrackingIdentity = Boolean(trackingOrderId || trackingOrderKey);
      if (!hasTrackingIdentity) {
        return;
      }

      const applyTrackingState = function (state) {
        if (!state || typeof state !== 'object') return;

        if (trackingText) {
          const message = String(state.message || '').trim();
          const mergedText = message
            ? (baseTrackingText ? baseTrackingText + ' | ' + message : message)
            : baseTrackingText;
          trackingText.textContent = mergedText;
        }

        if (trackingLink && state.url) {
          trackingLink.setAttribute('href', state.url);
        } else if (trackingLink && trackingOrderId) {
          try {
            const url = new URL(trackingLink.getAttribute('href') || '', window.location.origin);
            if (!url.searchParams.get('order_id')) {
              url.searchParams.set('order_id', trackingOrderId);
            }
            if (trackingOrderKey && !url.searchParams.get('key')) {
              url.searchParams.set('key', trackingOrderKey);
            }
            trackingLink.setAttribute('href', url.toString());
          } catch (error) {
            // Ignore malformed URL and keep existing href.
          }
        }

        updateTextNode(trackingScope, '[data-tracking-consignment-value]', state.consignment_id || state.order_id || trackingOrderId);
        updateTextNode(trackingScope, '[data-tracking-status-value]', state.status);
        updateTextNode(trackingScope, '[data-tracking-provider-value]', state.provider);
        updateTextNode(trackingScope, '[data-tracking-note-value]', state.note);
        const copyButton = trackingScope.querySelector('.goody-track-copy');
        const copyValue = String(state.consignment_id || state.order_id || trackingOrderId || '').trim();
        if (copyButton && copyValue) {
          copyButton.setAttribute('data-copy', copyValue);
        }
        updateDeliveryDetails(trackingScope, state);
        updateTrackingSteps(trackingScope, state);
        updateTrackingTimeline(trackingScope, state);
      };

      const loadTrackingState = function () {
        const formData = new FormData();
        formData.append('action', 'goody_tracking_status');
        formData.append('nonce', goodyTheme.nonce || '');
        if (trackingOrderId) {
          formData.append('order_id', trackingOrderId);
        }
        if (trackingOrderKey) {
          formData.append('order_key', trackingOrderKey);
        }

        const controller = typeof window.AbortController === 'function' ? new AbortController() : null;
        const timeoutId = window.setTimeout(function () {
          if (controller) {
            controller.abort();
          }
        }, 7000);

        fetch(goodyTheme.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData,
          signal: controller ? controller.signal : undefined
        })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (!data || !data.success || !data.data) return;
            applyTrackingState(data.data);
          })
          .catch(function () {
            // Keep current tracking text/link when request fails.
          })
          .finally(function () {
            window.clearTimeout(timeoutId);
          });
      };

      loadTrackingState();
      setInterval(loadTrackingState, 30000);
    });
  }

  document.querySelectorAll('.goody-track-copy').forEach(function (button) {
    button.addEventListener('click', function () {
      const text = String(button.getAttribute('data-copy') || '').trim();
      if (!text || !navigator.clipboard || !navigator.clipboard.writeText) return;

      navigator.clipboard.writeText(text)
        .then(function () {
          const originalText = button.textContent;
          button.textContent = 'Copied';
          window.setTimeout(function () {
            button.textContent = originalText;
          }, 1300);
        })
        .catch(function () {
          // Ignore clipboard errors silently.
      });
    });
  });

  const openDirectOrderModal = function (trigger) {
    const targetId = String(trigger.getAttribute('data-goody-direct-order-target') || '').trim();
    const modal = targetId ? document.getElementById(targetId) : document.querySelector('[data-goody-direct-order-modal]');
    if (!modal) return;

    const productInput = modal.querySelector('[data-goody-direct-order-product]');
    const quantityInput = modal.querySelector('[data-goody-direct-order-quantity]');
    const providerSelect = modal.querySelector('[data-goody-direct-order-provider]');
    const title = modal.querySelector('[data-goody-direct-order-title]');
    const price = modal.querySelector('[data-goody-direct-order-price]');
    const imageWrap = modal.querySelector('[data-goody-direct-order-image-wrap]');
    const image = modal.querySelector('[data-goody-direct-order-image]');
    const productId = String(trigger.getAttribute('data-product-id') || '').trim();
    const quantity = String(trigger.getAttribute('data-quantity') || '1').trim() || '1';
    const minQuantity = String(trigger.getAttribute('data-min-quantity') || '1').trim() || '1';
    const maxQuantity = String(trigger.getAttribute('data-max-quantity') || '').trim();
    const stepQuantity = String(trigger.getAttribute('data-step-quantity') || '1').trim() || '1';
    const itemTitle = String(trigger.getAttribute('data-title') || '').trim();
    const itemPrice = String(trigger.getAttribute('data-price') || '').trim();
    const itemImage = String(trigger.getAttribute('data-image') || '').trim();

    if (!productId || !productInput || !quantityInput || !providerSelect) return;

    productInput.value = productId;
    quantityInput.value = quantity;
    quantityInput.setAttribute('min', minQuantity);
    quantityInput.setAttribute('step', stepQuantity);
    if (maxQuantity) {
      quantityInput.setAttribute('max', maxQuantity);
    } else {
      quantityInput.removeAttribute('max');
    }

    providerSelect.value = '';

    if (title && itemTitle) {
      title.textContent = itemTitle;
    }

    if (price) {
      price.textContent = itemPrice;
      price.hidden = !itemPrice;
    }

    if (image && imageWrap) {
      if (itemImage) {
        image.src = itemImage;
        image.alt = itemTitle;
        imageWrap.hidden = false;
      } else {
        image.removeAttribute('src');
        image.alt = '';
        imageWrap.hidden = true;
      }
    }

    modal.hidden = false;
    modal.classList.add('is-open');
    modal._goodyLastFocus = trigger;
    document.body.classList.add('goody-direct-order-modal-open');

    window.setTimeout(function () {
      providerSelect.focus();
    }, 30);
  };

  const closeDirectOrderModal = function (modal) {
    if (!modal) return;

    modal.classList.remove('is-open');
    modal.hidden = true;
    document.body.classList.remove('goody-direct-order-modal-open');

    if (modal._goodyLastFocus && typeof modal._goodyLastFocus.focus === 'function') {
      modal._goodyLastFocus.focus();
    }
  };

  document.addEventListener('click', function (event) {
    const closeControl = event.target.closest('[data-goody-direct-order-close]');
    if (closeControl) {
      event.preventDefault();
      closeDirectOrderModal(closeControl.closest('[data-goody-direct-order-modal]'));
      return;
    }

    const directOrderTrigger = event.target.closest('[data-goody-direct-order-open]');
    if (!directOrderTrigger) return;

    event.preventDefault();
    openDirectOrderModal(directOrderTrigger);
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') return;

    document.querySelectorAll('[data-goody-direct-order-modal].is-open').forEach(function (modal) {
      closeDirectOrderModal(modal);
    });
  });

  document.querySelectorAll('[data-goody-single-provider-form]').forEach(function (form) {
    const providerSelect = form.querySelector('[data-goody-single-provider-select]');
    const actionLink = form.querySelector('[data-goody-single-provider-link]');
    if (!providerSelect || !actionLink) return;

    const syncSingleProviderLink = function () {
      const selectedOption = providerSelect.options[providerSelect.selectedIndex];
      const nextHref = String(selectedOption && selectedOption.value ? selectedOption.value : '').trim();
      if (nextHref) {
        actionLink.setAttribute('href', nextHref);
      }

      const isExternal = selectedOption && selectedOption.getAttribute('data-external') === '1';
      if (isExternal) {
        actionLink.setAttribute('target', '_blank');
        actionLink.setAttribute('rel', 'noopener noreferrer');
        return;
      }

      actionLink.removeAttribute('target');
      actionLink.removeAttribute('rel');
    };

    providerSelect.addEventListener('change', syncSingleProviderLink);
    syncSingleProviderLink();
  });

  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.site-navigation');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      const isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      document.body.classList.toggle('nav-open', isOpen);
    });
  }

  const searchModal = document.querySelector('[data-goody-search-modal]');
  if (searchModal && window.goodyTheme && goodyTheme.ajaxUrl) {
    const headerSearchForm = document.querySelector('[data-goody-search-trigger-form]');
    const headerSearchInput = headerSearchForm ? headerSearchForm.querySelector('[data-goody-search-trigger-input]') : null;
    const openSearchButtons = document.querySelectorAll('[data-goody-search-open]');
    const closeSearchButtons = searchModal.querySelectorAll('[data-goody-search-close]');
    const searchForm = searchModal.querySelector('[data-goody-search-form]');
    const searchInput = searchModal.querySelector('[data-goody-search-input]');
    const searchMeta = searchModal.querySelector('[data-goody-search-meta]');
    const searchResults = searchModal.querySelector('[data-goody-search-results]');
    let searchDebounceTimer;
    let searchRequestToken = 0;
    let isSearchOpen = false;

    const setSearchMeta = function (message) {
      if (!searchMeta) return;
      searchMeta.textContent = String(message || '').trim();
    };

    const closeSearchModal = function () {
      if (!isSearchOpen) return;
      isSearchOpen = false;
      searchModal.hidden = true;
      document.body.classList.remove('goody-search-open');
      if (searchModal._goodyLastFocus && typeof searchModal._goodyLastFocus.focus === 'function') {
        searchModal._goodyLastFocus.focus();
      }
    };

    const openSearchModal = function (trigger) {
      if (isSearchOpen) return;
      isSearchOpen = true;
      searchModal.hidden = false;
      document.body.classList.add('goody-search-open');
      searchModal._goodyLastFocus = trigger || document.activeElement;

      if (searchInput) {
        const initialTerm = String(headerSearchInput && headerSearchInput.value ? headerSearchInput.value : '').trim();
        searchInput.value = initialTerm;
        window.setTimeout(function () {
          searchInput.focus();
          searchInput.select();
        }, 24);
        if (initialTerm.length >= 2) {
          runSearch(initialTerm);
        } else if (searchResults) {
          searchResults.innerHTML = '';
          setSearchMeta('Start typing to find menu items, offers, events, and posts.');
        }
      }
    };

    const renderLoadingState = function () {
      if (!searchResults) return;
      searchResults.classList.add('is-loading');
    };

    const clearLoadingState = function () {
      if (!searchResults) return;
      searchResults.classList.remove('is-loading');
    };

    const runSearch = function (rawTerm) {
      const term = String(rawTerm || '').trim();
      if (!searchResults) return;
      if (term.length < 2) {
        searchResults.innerHTML = '';
        setSearchMeta('Type at least 2 characters to search.');
        clearLoadingState();
        return;
      }

      const requestToken = searchRequestToken + 1;
      searchRequestToken = requestToken;
      renderLoadingState();
      setSearchMeta('Searching...');

      const formData = new FormData();
      formData.append('action', 'goody_search');
      formData.append('nonce', goodyTheme.nonce || '');
      formData.append('q', term);

      fetch(goodyTheme.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (payload) {
          if (requestToken !== searchRequestToken) return;
          if (!payload || !payload.success || !payload.data) {
            searchResults.innerHTML = '<div class="card empty-state"><h3>Search unavailable</h3><p>Please try again.</p></div>';
            setSearchMeta('Search unavailable.');
            return;
          }

          const html = String(payload.data.html || '');
          const count = Number(payload.data.count || 0);
          searchResults.innerHTML = html;
          setSearchMeta(count > 0 ? count + ' result' + (count > 1 ? 's' : '') + ' found.' : 'No results found.');
        })
        .catch(function () {
          if (requestToken !== searchRequestToken) return;
          searchResults.innerHTML = '<div class="card empty-state"><h3>Search unavailable</h3><p>Please check connection and try again.</p></div>';
          setSearchMeta('Search unavailable.');
        })
        .finally(function () {
          if (requestToken !== searchRequestToken) return;
          clearLoadingState();
        });
    };

    openSearchButtons.forEach(function (button) {
      button.addEventListener('click', function (event) {
        event.preventDefault();
        openSearchModal(button);
      });
    });

    closeSearchButtons.forEach(function (button) {
      button.addEventListener('click', function (event) {
        event.preventDefault();
        closeSearchModal();
      });
    });

    if (headerSearchForm) {
      headerSearchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        openSearchModal(headerSearchForm.querySelector('[data-goody-search-open]'));
      });
    }

    if (searchInput) {
      searchInput.addEventListener('input', function () {
        const nextTerm = searchInput.value || '';
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(function () {
          runSearch(nextTerm);
        }, 240);
      });
    }

    if (searchForm && searchInput) {
      searchForm.addEventListener('submit', function (event) {
        const term = String(searchInput.value || '').trim();
        if (term.length < 2) return;
        event.preventDefault();
        runSearch(term);
      });
    }

    if (searchResults) {
      searchResults.addEventListener('click', function (event) {
        const resultLink = event.target.closest('.goody-search-result');
        if (!resultLink) return;
        closeSearchModal();
      });
    }

    document.addEventListener('keydown', function (event) {
      if (!isSearchOpen) return;
      if (event.key === 'Escape') {
        event.preventDefault();
        closeSearchModal();
      }
    });
  }

  const filterForm = document.querySelector('.menu-filters');
  const menuResults = document.querySelector('[data-menu-results]');
  const menuCount = document.querySelector('[data-menu-count]');

  if (filterForm && menuResults && window.goodyTheme && goodyTheme.ajaxUrl) {
    let timer;
    const advancedToggle = filterForm.querySelector('[data-filter-advanced-toggle]');
    const advancedFilters = filterForm.querySelector('#menu-advanced-filters');
    const resetBtn = filterForm.querySelector('[data-filter-reset]');

    const syncFilterChips = function () {
      const chips = filterForm.querySelectorAll('[data-filter-chip]');

      chips.forEach(function (chip) {
        const targetName = chip.getAttribute('data-filter-target');
        const target = targetName ? filterForm.querySelector('[name="' + targetName + '"]') : null;
        const expectedValue = chip.getAttribute('data-filter-value') || '';
        const isActive = !!target && (target.value || '') === expectedValue;

        chip.classList.toggle('is-active', isActive);
        chip.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });
    };

    const setAdvancedFilterState = function (isOpen) {
      if (!advancedToggle || !advancedFilters) return;

      advancedToggle.classList.toggle('is-open', isOpen);
      advancedToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      advancedFilters.hidden = !isOpen;
      filterForm.classList.toggle('is-advanced-open', isOpen);

      if (resetBtn) {
        resetBtn.hidden = !isOpen;
      }
    };

    const applyFilters = function () {
      const formData = new FormData(filterForm);
      formData.append('action', 'goody_filter_menu');
      formData.append('nonce', goodyTheme.nonce || '');

      menuResults.classList.add('is-loading');

      fetch(goodyTheme.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (data) {
          if (!data || !data.success) return;

          menuResults.innerHTML = data.data.html || '';
          if (menuCount) {
            menuCount.textContent = data.data.found_posts + ' dishes';
          }
        })
        .catch(function () {
          menuResults.innerHTML = '<div class="card empty-state"><h3>Something went wrong</h3><p>Please try again.</p></div>';
        })
        .finally(function () {
          menuResults.classList.remove('is-loading');
        });
    };

    filterForm.addEventListener('change', function () {
      applyFilters();
    });

    if (advancedToggle && advancedFilters) {
      setAdvancedFilterState(false);

      advancedToggle.addEventListener('click', function () {
        setAdvancedFilterState(advancedFilters.hidden);
      });
    }

    filterForm.querySelectorAll('[data-filter-chip]').forEach(function (chip) {
      chip.addEventListener('click', function () {
        const targetName = chip.getAttribute('data-filter-target');
        const target = targetName ? filterForm.querySelector('[name="' + targetName + '"]') : null;
        if (!target) return;

        target.value = chip.getAttribute('data-filter-value') || '';
        syncFilterChips();
        applyFilters();
      });
    });

    const searchInput = filterForm.querySelector('[name="q"]');
    if (searchInput) {
      searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
          applyFilters();
        }, 350);
      });
    }

    if (resetBtn) {
      resetBtn.addEventListener('click', function (e) {
        e.preventDefault();
        filterForm.reset();
        setAdvancedFilterState(false);
        syncFilterChips();
        applyFilters();
      });
    }

    syncFilterChips();
  }

  const carousel = document.querySelector('.testimonials-carousel');
  if (carousel) {
    const prev = document.querySelector('[data-testimonial-prev]');
    const next = document.querySelector('[data-testimonial-next]');

    const scrollAmount = function () {
      return Math.max(320, carousel.clientWidth * 0.7);
    };

    if (prev) {
      prev.addEventListener('click', function () {
        carousel.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
      });
    }

    if (next) {
      next.addEventListener('click', function () {
        carousel.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
      });
    }
  }

  const reviewModal = document.querySelector('[data-review-modal]');
  if (reviewModal) {
    const reviewSectionContainer = document.querySelector('#reviews .container');
    const openButtons = document.querySelectorAll('[data-open-review-form]');
    const closeButtons = reviewModal.querySelectorAll('[data-close-review-form]');
    const reviewForm = reviewModal.querySelector('.review-form');
    const reviewOrderInput = reviewModal.querySelector('[data-review-order-id]');
    const reviewOrderUnlockButton = reviewModal.querySelector('[data-review-order-unlock]');
    const reviewOrderMessage = reviewModal.querySelector('[data-review-order-message]');
    const reviewFields = reviewModal.querySelector('[data-review-fields]');
    const reviewNameInput = reviewModal.querySelector('input[name="goody_testimonial_name"]');
    let reviewOrderValidationPending = false;

    const isValidReviewOrderId = function (value) {
      return /^[0-9]+$/.test(value) && Number(value) > 0;
    };

    const setReviewOrderMessage = function (message) {
      if (!reviewOrderMessage) return;

      if (!message) {
        reviewOrderMessage.textContent = '';
        reviewOrderMessage.hidden = true;
        return;
      }

      reviewOrderMessage.textContent = message;
      reviewOrderMessage.hidden = false;
    };

    const setReviewFieldsState = function (unlocked) {
      if (!reviewFields) return;
      reviewFields.hidden = !unlocked;
    };

    const setReviewOrderValidationPending = function (pending) {
      reviewOrderValidationPending = !!pending;
      if (!reviewOrderUnlockButton) return;
      reviewOrderUnlockButton.disabled = reviewOrderValidationPending;
    };

    const validateReviewOrderIdRemotely = function (orderIdValue) {
      if (!(window.goodyTheme && goodyTheme.ajaxUrl && goodyTheme.nonce)) {
        return Promise.resolve({
          valid: true,
          message: ''
        });
      }

      const formData = new FormData();
      formData.append('action', 'goody_validate_testimonial_order');
      formData.append('nonce', goodyTheme.nonce);
      formData.append('order_id', orderIdValue);

      return fetch(goodyTheme.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (response) {
          if (response && response.success) {
            return {
              valid: true,
              message: ''
            };
          }

          const message = response && response.data && response.data.message
            ? String(response.data.message)
            : 'Please enter a valid order ID first.';

          return {
            valid: false,
            message: message
          };
        })
        .catch(function () {
          return {
            valid: false,
            message: 'Could not verify order ID right now. Please try again.'
          };
        });
    };

    const unlockReviewFields = async function () {
      if (!reviewOrderInput || !reviewFields) return true;

      const orderIdValue = String(reviewOrderInput.value || '').trim();
      if (!isValidReviewOrderId(orderIdValue)) {
        setReviewFieldsState(false);
        setReviewOrderMessage('Please enter a valid order ID first.');
        reviewOrderInput.focus();
        return false;
      }

      if (reviewOrderValidationPending) {
        return false;
      }

      setReviewOrderValidationPending(true);
      const validation = await validateReviewOrderIdRemotely(orderIdValue);
      setReviewOrderValidationPending(false);

      if (!validation.valid) {
        setReviewFieldsState(false);
        setReviewOrderMessage(validation.message || 'Please enter a valid order ID first.');
        reviewOrderInput.focus();
        return false;
      }

      setReviewOrderMessage('');
      setReviewFieldsState(true);
      if (reviewNameInput) {
        reviewNameInput.focus();
      }
      return true;
    };

    const resetReviewUnlockState = function () {
      setReviewOrderValidationPending(false);
      setReviewOrderMessage('');
      setReviewFieldsState(false);
    };

    const setReviewModalState = function (open) {
      reviewModal.hidden = !open;
      document.body.classList.toggle('review-modal-open', open);

      if (open) {
        resetReviewUnlockState();
        if (reviewOrderInput) {
          reviewOrderInput.focus();
        }
      }
    };

    if (reviewOrderUnlockButton) {
      reviewOrderUnlockButton.addEventListener('click', async function (event) {
        event.preventDefault();
        await unlockReviewFields();
      });
    }

    if (reviewOrderInput) {
      reviewOrderInput.addEventListener('keydown', async function (event) {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        await unlockReviewFields();
      });

      reviewOrderInput.addEventListener('input', function () {
        setReviewOrderMessage('');
        if (!reviewFields || reviewFields.hidden) return;
        if (!isValidReviewOrderId(String(reviewOrderInput.value || '').trim())) {
          setReviewFieldsState(false);
        }
      });
    }

    if (reviewForm) {
      reviewForm.addEventListener('submit', function (event) {
        if (!reviewFields || !reviewFields.hidden) return;
        event.preventDefault();
        unlockReviewFields();
      });
    }

    openButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        setReviewModalState(true);
      });
    });

    closeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        setReviewModalState(false);
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !reviewModal.hidden) {
        setReviewModalState(false);
      }
    });

  if (reviewSectionContainer && reviewSectionContainer.getAttribute('data-review-open-default') === '1') {
      setReviewModalState(true);
    }
  }

  const checkoutSummary = document.querySelector('[data-goody-checkout-summary]');
  const checkoutSummaryToggle = document.querySelector('[data-goody-summary-toggle]');
  const checkoutSummaryContent = document.querySelector('[data-goody-summary-content]');
  if (checkoutSummary && checkoutSummaryToggle && checkoutSummaryContent) {
    const setSummaryState = function (expanded) {
      checkoutSummaryToggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      checkoutSummary.classList.toggle('is-open', expanded);
      checkoutSummaryContent.hidden = !expanded && window.innerWidth <= 900;
    };

    setSummaryState(window.innerWidth > 900);

    checkoutSummaryToggle.addEventListener('click', function () {
      const isExpanded = checkoutSummaryToggle.getAttribute('aria-expanded') === 'true';
      setSummaryState(!isExpanded);
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth > 900) {
        setSummaryState(true);
      } else {
        setSummaryState(checkoutSummary.classList.contains('is-open'));
      }
    });
  }

  const checkoutForm = document.querySelector('form.checkout');
  if (checkoutForm) {
    const phoneField = checkoutForm.querySelector('#billing_phone');
    const emailField = checkoutForm.querySelector('#billing_email');
    const addressField = checkoutForm.querySelector('#billing_address_1');
    const placeOrderButton = checkoutForm.querySelector('#place_order');
    const phoneBlocked = new Set(['00000000', '11111111', '12345678', '99999999', '0123456789']);

    const ensureMessageNode = function (field) {
      if (!field) return null;
      const wrap = field.closest('.form-row') || field.parentElement;
      if (!wrap) return null;
      let message = wrap.querySelector('.goody-inline-error');
      if (!message) {
        message = document.createElement('small');
        message.className = 'goody-inline-error';
        wrap.appendChild(message);
      }
      return message;
    };

    const setFieldState = function (field, valid, messageText) {
      if (!field) return;
      field.setAttribute('aria-invalid', valid ? 'false' : 'true');
      const message = ensureMessageNode(field);
      if (!message) return;
      message.textContent = valid ? '' : messageText;
      message.hidden = valid;
    };

    const validatePhone = function () {
      if (!phoneField) return true;
      const raw = String(phoneField.value || '').trim();
      if (!raw) {
        setFieldState(phoneField, false, 'Phone number is required.');
        return false;
      }
      if (!/^[0-9+\-\s]+$/.test(raw)) {
        setFieldState(phoneField, false, 'Use only numbers, +, space, or hyphen.');
        return false;
      }
      const digits = raw.replace(/\D+/g, '');
      if (digits.length < 8 || digits.length > 15) {
        setFieldState(phoneField, false, 'Phone number must be 8 to 15 digits.');
        return false;
      }
      if (phoneBlocked.has(digits) || /^(\d)\1{7,}$/.test(digits)) {
        setFieldState(phoneField, false, 'Please enter a real phone number.');
        return false;
      }
      setFieldState(phoneField, true, '');
      return true;
    };

    const validateEmail = function () {
      if (!emailField) return true;
      const raw = String(emailField.value || '').trim();
      if (!raw) {
        setFieldState(emailField, false, 'Email address is required.');
        return false;
      }
      const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(raw);
      setFieldState(emailField, emailOk, emailOk ? '' : 'Please enter a valid email address.');
      return emailOk;
    };

    const validateAddress = function () {
      if (!addressField) return true;
      const raw = String(addressField.value || '').trim();
      const ok = raw.length > 0;
      setFieldState(addressField, ok, ok ? '' : 'Full address is required.');
      return ok;
    };

    const validateCheckoutForm = function () {
      const ok = validatePhone() && validateEmail() && validateAddress();
      if (placeOrderButton) {
        placeOrderButton.disabled = !ok;
        placeOrderButton.classList.toggle('is-disabled', !ok);
      }
      return ok;
    };

    [phoneField, emailField, addressField].forEach(function (field) {
      if (!field) return;
      field.addEventListener('input', validateCheckoutForm);
      field.addEventListener('change', validateCheckoutForm);
      field.addEventListener('blur', validateCheckoutForm);
    });

    checkoutForm.addEventListener('submit', function (event) {
      if (validateCheckoutForm()) return;
      event.preventDefault();
      event.stopPropagation();
    });

    validateCheckoutForm();
  }

  const blockCheckout = document.querySelector('.wp-block-woocommerce-checkout');
  if (blockCheckout) {
    const hideFieldSelectors = [
      '.wc-block-components-address-form__first_name',
      '.wc-block-components-address-form__last_name',
      '.wc-block-components-address-form__company',
      '.wc-block-components-address-form__country',
      '.wc-block-components-address-form__address_2',
      '.wc-block-components-address-form__city',
      '.wc-block-components-address-form__state',
      '.wc-block-components-address-form__postcode',
      '.wc-block-components-checkout-step--additional-information',
      '.wc-block-checkout__shipping-fields'
    ];

    let blockRulesApplying = false;
    let blockRulesQueued = false;

    const queueBlockRules = function () {
      if (blockRulesQueued) return;
      blockRulesQueued = true;
      window.requestAnimationFrame(function () {
        blockRulesQueued = false;
        applyBlockCheckoutFieldRules();
      });
    };

    const applyBlockCheckoutFieldRules = function () {
      if (blockRulesApplying) return;
      blockRulesApplying = true;

      hideFieldSelectors.forEach(function (selector) {
        blockCheckout.querySelectorAll(selector).forEach(function (node) {
          if (!node) return;
          if (node.style.display !== 'none') {
            node.style.display = 'none';
          }
        });
      });

      // Keep order as Email -> Phone -> Address in Checkout Block forms.
      blockCheckout.querySelectorAll('.wc-block-components-address-form').forEach(function (form) {
        const emailField = form.querySelector('.wc-block-components-address-form__email');
        const phoneField = form.querySelector('.wc-block-components-address-form__phone');
        const addressField = form.querySelector('.wc-block-components-address-form__address_1');

        if (
          emailField &&
          phoneField &&
          emailField.parentNode === phoneField.parentNode &&
          emailField.nextElementSibling !== phoneField
        ) {
          emailField.parentNode.insertBefore(phoneField, emailField.nextSibling);
        }
        if (
          phoneField &&
          addressField &&
          phoneField.parentNode === addressField.parentNode &&
          phoneField.nextElementSibling !== addressField
        ) {
          phoneField.parentNode.insertBefore(addressField, phoneField.nextSibling);
        }

        const phoneInput = form.querySelector('input[name="billing_phone"]');
        if (phoneInput) {
          phoneInput.required = true;
          phoneInput.setAttribute('aria-required', 'true');
        }

        if (phoneField) {
          const phoneLabel = phoneField.querySelector('label');
          if (phoneLabel) {
            const cleanedText = String(phoneLabel.textContent || '').replace(/\(optional\)/ig, '').trim();
            if (cleanedText) {
              phoneLabel.textContent = cleanedText;
            }
          }
        }
      });

      const defaults = [
        { selector: 'input[name="billing_first_name"]', value: 'Customer' },
        { selector: 'input[name="billing_last_name"]', value: 'Guest' },
        { selector: 'input[name="billing_city"]', value: 'N/A' },
        { selector: 'input[name="billing_postcode"]', value: '0000' }
      ];

      defaults.forEach(function (item) {
        const input = blockCheckout.querySelector(item.selector);
        if (!input) return;
        if (String(input.value || '').trim()) return;
        input.value = item.value;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });

      const countrySelect = blockCheckout.querySelector('select[name="billing_country"]');
      if (countrySelect && String(countrySelect.value || '').trim() === '') {
        const fallbackValue = countrySelect.querySelector('option[value="BD"]') ? 'BD' : (countrySelect.value || '');
        if (fallbackValue) {
          countrySelect.value = fallbackValue;
          countrySelect.dispatchEvent(new Event('change', { bubbles: true }));
        }
      }

      blockRulesApplying = false;
    };

    queueBlockRules();
    const blockObserver = new MutationObserver(function () {
      queueBlockRules();
    });
    blockObserver.observe(blockCheckout, { childList: true, subtree: true });
  }
});
