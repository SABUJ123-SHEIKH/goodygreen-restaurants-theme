(function () {
  function cleanCouponMarkup() {
    var couponRoot = document.querySelector('.goody-checkout-summary-card__coupon');
    if (!couponRoot) {
      return;
    }
    couponRoot.querySelectorAll('br').forEach(function (node) {
      node.remove();
    });
  }

  cleanCouponMarkup();
  if (window.jQuery) {
    window.jQuery(document.body).on('updated_checkout', cleanCouponMarkup);
  }

  var providerInput = document.querySelector('[data-goody-provider-input]');
  var options = document.querySelectorAll('[data-goody-provider-option]');
  var estimate = document.querySelector('[data-goody-estimate]');
  var selectedLabel = document.querySelector('[data-goody-provider-selected-label]');

  if (!providerInput || !options.length) {
    return;
  }

  function setCollapsedMode(value, collapsed) {
    options.forEach(function (option) {
      var isActive = option.getAttribute('data-goody-provider-option') === value;
      option.hidden = collapsed && !isActive;
    });
  }

  function updateUI(value, collapsed) {
    var activeOption = null;

    options.forEach(function (option) {
      var active = option.getAttribute('data-goody-provider-option') === value;
      option.classList.toggle('is-active', active);
      option.setAttribute('aria-checked', active ? 'true' : 'false');
      if (active) {
        activeOption = option;
      }
    });

    if (estimate) {
      estimate.textContent = (activeOption && activeOption.getAttribute('data-goody-provider-eta')) || '30–45 min';
    }

    if (selectedLabel && activeOption) {
      var label = activeOption.querySelector('strong');
      selectedLabel.textContent = label ? label.textContent : value;
    }

    setCollapsedMode(value, collapsed);
  }

  options.forEach(function (option) {
    option.addEventListener('click', function () {
      var value = option.getAttribute('data-goody-provider-option');
      providerInput.value = value;
      updateUI(value, true);
      cleanCouponMarkup();
    });
  });

  var fallback = options[0] ? options[0].getAttribute('data-goody-provider-option') : '';
  updateUI(providerInput.value || fallback, true);
  cleanCouponMarkup();
})();
