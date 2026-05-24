document.addEventListener('DOMContentLoaded', function () {
  var themeConfig = window.goodyReservationTheme || {};
  var ajaxUrl = themeConfig.ajaxUrl || '';
  var nonce = themeConfig.nonce || '';
  var currencySymbol = themeConfig.currencySymbol || '$';
  var locale = themeConfig.locale || 'en-US';

  function normalizeLocaleTag(value) {
    var fallback = 'en-US';
    var candidate = String(value || '').trim().replace(/_/g, '-');

    if (!candidate) {
      return fallback;
    }

    try {
      return Intl.getCanonicalLocales(candidate)[0] || fallback;
    } catch (error) {
      return fallback;
    }
  }

  locale = normalizeLocaleTag(locale);

  function parseJsonConfig(element) {
    if (!element) {
      return {};
    }

    try {
      return JSON.parse(element.textContent || '{}');
    } catch (error) {
      return {};
    }
  }

  function toNumber(value, fallback) {
    var number = Number(value);
    return Number.isFinite(number) ? number : fallback;
  }

  function toArray(value) {
    return Array.isArray(value) ? value : [];
  }

  function request(action, payload) {
    var formData = new FormData();
    formData.append('action', action);
    formData.append('nonce', nonce);

    Object.keys(payload || {}).forEach(function (key) {
      formData.append(key, payload[key]);
    });

    return fetch(ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData
    }).then(function (response) {
      return response.json().then(function (data) {
        if (!response.ok || !data || data.success !== true) {
          var message = data && data.data && data.data.message ? data.data.message : 'Something went wrong.';
          throw new Error(message);
        }

        return data.data;
      });
    });
  }

  function formatAmount(amount) {
    var number = toNumber(amount, 0);
    var formatterLocale = normalizeLocaleTag(locale);

    try {
      return currencySymbol + new Intl.NumberFormat(formatterLocale, {
        minimumFractionDigits: number % 1 === 0 ? 0 : 2,
        maximumFractionDigits: 2
      }).format(number);
    } catch (error) {
      return currencySymbol + number.toFixed(number % 1 === 0 ? 0 : 2);
    }
  }

  function validateFlowBeforeQuote(validateStep) {
    validateStep(1);
    validateStep(2);
    validateStep(3);
    validateStep(4);
    validateStep(5);
  }

  function initMenuFilterApp(menuApp) {
    if (!menuApp || menuApp.getAttribute('data-goody-menu-filter-bound') === '1') {
      return;
    }

    var filterButtons = Array.prototype.slice.call(menuApp.querySelectorAll('[data-menu-filter]'));
    var itemCards = Array.prototype.slice.call(menuApp.querySelectorAll('[data-menu-item-id]'));
    if (!filterButtons.length || !itemCards.length) {
      return;
    }

    menuApp.setAttribute('data-goody-menu-filter-bound', '1');
    var menuGrid = menuApp.querySelector('.goody-booking-menu__grid');
    var emptyState = menuApp.querySelector('[data-menu-filter-empty]');

    if (!emptyState && menuGrid) {
      emptyState = document.createElement('div');
      emptyState.className = 'goody-booking-menu__empty goody-inline-empty';
      emptyState.setAttribute('data-menu-filter-empty', '1');
      emptyState.hidden = true;
      emptyState.textContent = 'No menu items found in this category.';
      menuGrid.insertAdjacentElement('afterend', emptyState);
    }

    function applyMenuFilter(filter) {
      var visibleCount = 0;

      itemCards.forEach(function (card) {
        var categories = String(card.getAttribute('data-category-ids') || '').split(',').map(function (category) {
          return category.trim();
        }).filter(Boolean);
        var showCard = filter === 'all' || categories.indexOf(filter) !== -1;

        card.hidden = !showCard;
        card.classList.toggle('is-filter-hidden', !showCard);
        card.setAttribute('aria-hidden', showCard ? 'false' : 'true');

        if (showCard) {
          visibleCount += 1;
        }
      });

      if (emptyState) {
        emptyState.hidden = visibleCount > 0;
      }
    }

    menuApp.addEventListener('click', function (event) {
      var button = event.target.closest('[data-menu-filter]');
      if (!button || !menuApp.contains(button)) {
        return;
      }

      event.preventDefault();

      var filter = String(button.getAttribute('data-menu-filter') || 'all');
      filterButtons.forEach(function (filterButton) {
        var isActive = filterButton === button;
        filterButton.classList.toggle('is-active', isActive);
        filterButton.setAttribute('aria-selected', isActive ? 'true' : 'false');
      });

      applyMenuFilter(filter);
    });

    var activeFilter = filterButtons.find(function (button) {
      return button.classList.contains('is-active') || button.getAttribute('aria-selected') === 'true';
    });
    applyMenuFilter(String((activeFilter || filterButtons[0]).getAttribute('data-menu-filter') || 'all'));
  }

  function initReservationApp(app) {
    var config = parseJsonConfig(app.querySelector('.goody-reservation-config'));
    var dates = toArray(config.dates);
    var menuItems = toArray(config.menuItems);
    var stepTitles = toArray(config.steps);
    var texts = config.texts || {};
    var settings = config.settings || {};
    var orderTypes = config.orderTypes || {};
    var paymentModes = config.paymentModes || {};
    var deliveryProviders = config.deliveryProviders || {};
    var fieldSettings = config.fieldSettings || {};
    var panels = Array.prototype.slice.call(app.querySelectorAll('[data-step-panel]'));
    var markers = Array.prototype.slice.call(app.querySelectorAll('[data-step-marker]'));
    var slotResults = app.querySelector('[data-slot-results]');
    var tableResults = app.querySelector('[data-table-results]');
    var liveSummary = app.querySelector('[data-live-summary]');
    var finalSummary = app.querySelector('[data-final-summary]');
    var addressWrap = app.querySelector('[data-address-wrap]');
    var providerWrap = app.querySelector('[data-delivery-provider-wrap]');
    var providerSelect = app.querySelector('[data-delivery-provider]');
    var paymentWarning = app.querySelector('[data-payment-warning]');
    var finalMessage = app.querySelector('[data-final-message]');
    var itemCards = Array.prototype.slice.call(app.querySelectorAll('[data-menu-item-id]'));
    var filterButtons = Array.prototype.slice.call(app.querySelectorAll('[data-menu-filter]'));
    var stepCounter = app.querySelector('[data-step-counter]');
    var currentStepTitle = app.querySelector('[data-current-step-title]');
    var progressFill = app.querySelector('[data-progress-fill]');
    var orderTypeKeys = Object.keys(orderTypes);
    var paymentModeKeys = Object.keys(paymentModes);
    var state = {
      step: 1,
      bookingDayId: '',
      bookingDate: '',
      slotTime: '',
      tableId: '',
      tableLabel: '',
      tableLocation: '',
      orderType: orderTypeKeys[0] || 'dine_in',
      paymentMode: paymentModeKeys[0] || 'full',
      deliveryProvider: '',
      guests: 1,
      customer: {
        name: '',
        phone: '',
        address: '',
        note: ''
      },
      items: {}
    };
    var lastQuoteHtml = '';

    function getText(key, fallback) {
      return typeof texts[key] === 'string' && texts[key] !== '' ? texts[key] : fallback;
    }

    function getDateBySelection(id, dateValue) {
      return dates.find(function (date) {
        return String(date.id) === String(id) && String(date.date || '') === String(dateValue || '');
      }) || null;
    }

    function getMenuItemById(id) {
      return menuItems.find(function (item) {
        return String(item.id) === String(id);
      }) || null;
    }

    function getSelectedItemsPayload() {
      return Object.keys(state.items).map(function (id) {
        return state.items[id];
      });
    }

    function getCapacityNeeds() {
      var personNeed = Math.max(1, toNumber(state.guests, 1));
      var kgNeed = 0;

      Object.keys(state.items).forEach(function (id) {
        var selection = state.items[id];
        var menuItem = getMenuItemById(id);
        if (!menuItem || !selection) {
          return;
        }

        if (menuItem.capacity_unit === 'person') {
          personNeed += toNumber(menuItem.capacity_value, 1) * toNumber(selection.qty, 0);
        } else if (menuItem.capacity_unit === 'kg') {
          kgNeed += toNumber(menuItem.capacity_value, 1) * toNumber(selection.qty, 0);
        }
      });

      return {
        personNeed: personNeed,
        kgNeed: kgNeed
      };
    }

    function calculateLocalTotals() {
      var subtotal = 0;

      Object.keys(state.items).forEach(function (id) {
        var selection = state.items[id];
        var menuItem = getMenuItemById(id);
        if (!menuItem || !selection) {
          return;
        }

        var qty = toNumber(selection.qty, 0);
        subtotal += toNumber(menuItem.price, 0) * qty;

        toArray(selection.addons).forEach(function (addonKey) {
          var addon = toArray(menuItem.addons).find(function (row) {
            return row.key === addonKey;
          });

          if (addon) {
            subtotal += toNumber(addon.price, 0) * qty;
          }
        });
      });

      return {
        subtotal: subtotal,
        delivery: 0,
        total: subtotal
      };
    }

    function renderLocalSummary() {
      if (!liveSummary) {
        return;
      }

      if (lastQuoteHtml) {
        liveSummary.innerHTML = lastQuoteHtml;
        return;
      }

      var lines = [];
      var date = getDateBySelection(state.bookingDayId, state.bookingDate);
      var totals = calculateLocalTotals();

      if (date) {
        lines.push('<p><strong>' + getText('labelDate', 'Date') + ':</strong> ' + date.display + '</p>');
      }

      if (state.slotTime) {
        lines.push('<p><strong>' + getText('labelSlot', 'Time slot') + ':</strong> ' + state.slotTime + '</p>');
      }
      if (state.tableLabel) {
        lines.push('<p><strong>' + getText('labelTable', 'Table') + ':</strong> ' + state.tableLabel + (state.tableLocation ? ' • ' + state.tableLocation : '') + '</p>');
      }

      if (state.orderType) {
        lines.push('<p><strong>' + getText('labelOrderType', 'Order type') + ':</strong> ' + (orderTypes[state.orderType] || state.orderType) + '</p>');
      }

      if (state.paymentMode) {
        lines.push('<p><strong>' + getText('labelPayment', 'Payment') + ':</strong> ' + (paymentModes[state.paymentMode] || state.paymentMode) + '</p>');
      }

      if (state.orderType === 'delivery' && state.deliveryProvider) {
        lines.push('<p><strong>' + getText('labelDeliveryProvider', 'Delivery provider') + ':</strong> ' + (deliveryProviders[state.deliveryProvider] || state.deliveryProvider) + '</p>');
      }

      lines.push('<p><strong>' + getText('labelGuests', 'Guests') + ':</strong> ' + Math.max(1, toNumber(state.guests, 1)) + '</p>');
      lines.push('<p><strong>' + getText('labelSubtotal', 'Subtotal') + ':</strong> ' + formatAmount(totals.subtotal) + '</p>');

      if (state.orderType === 'delivery') {
        lines.push('<p><strong>' + getText('labelDelivery', 'Delivery') + ':</strong> ' + formatAmount(totals.delivery) + '</p>');
      }

      lines.push('<p><strong>' + getText('labelTotal', 'Total') + ':</strong> ' + formatAmount(totals.total) + '</p>');

      if (!state.bookingDayId && !getSelectedItemsPayload().length) {
        liveSummary.innerHTML = '<p>' + getText('summaryPlaceholder', 'Your selected date, menu, slot, and totals will update here.') + '</p>';
        return;
      }

      liveSummary.innerHTML = lines.join('');
    }

    function getSelectedSlotButton() {
      if (!slotResults || !state.slotTime) {
        return null;
      }
      return slotResults.querySelector('[data-slot="' + state.slotTime + '"]');
    }

    function getSlotAllowedTypes() {
      var slotButton = getSelectedSlotButton();
      if (!slotButton) {
        return [];
      }

      var raw = String(slotButton.getAttribute('data-slot-types') || '').trim();
      if (!raw) {
        return [];
      }

      return raw.split(',').map(function (value) {
        return String(value || '').trim();
      }).filter(Boolean);
    }

    function syncOrderTypesBySelectedSlot() {
      var allowedTypes = getSlotAllowedTypes();
      var allowedSet = {};
      var hasRestriction = allowedTypes.length > 0;
      allowedTypes.forEach(function (typeKey) {
        allowedSet[typeKey] = true;
      });

      var orderTypeButtons = Array.prototype.slice.call(app.querySelectorAll('[data-order-type]'));
      var visibleOrderTypes = [];
      orderTypeButtons.forEach(function (button) {
        var typeKey = String(button.getAttribute('data-order-type') || '');
        var isAllowed = !hasRestriction || !!allowedSet[typeKey];
        button.hidden = !isAllowed;
        button.disabled = !isAllowed;

        if (isAllowed && typeKey) {
          visibleOrderTypes.push(typeKey);
        }
      });

      if (visibleOrderTypes.length === 0) {
        state.orderType = '';
        return;
      }

      if (!state.orderType || visibleOrderTypes.indexOf(state.orderType) === -1) {
        state.orderType = visibleOrderTypes[0];
      }
    }

    function setStep(step) {
      state.step = step;

      panels.forEach(function (panel) {
        panel.classList.toggle('is-active', Number(panel.getAttribute('data-step-panel')) === step);
      });

      markers.forEach(function (marker) {
        var markerStep = Number(marker.getAttribute('data-step-marker'));
        marker.classList.toggle('is-active', markerStep === step);
        marker.classList.toggle('is-complete', markerStep < step);
      });

      if (stepCounter) {
        stepCounter.textContent = getText('stepCounterPrefix', 'Step') + ' ' + step + '/6';
      }

      if (currentStepTitle) {
        currentStepTitle.textContent = stepTitles[step - 1] || '';
      }

      if (progressFill) {
        progressFill.style.width = String(((step - 1) / 5) * 100) + '%';
      }
    }

    function syncSelectionUI() {
      Array.prototype.slice.call(app.querySelectorAll('[data-booking-day]')).forEach(function (button) {
        button.classList.toggle(
          'is-selected',
          String(button.getAttribute('data-booking-day')) === String(state.bookingDayId) &&
          String(button.getAttribute('data-booking-date') || '') === String(state.bookingDate || '')
        );
      });

      Array.prototype.slice.call(app.querySelectorAll('[data-order-type]')).forEach(function (button) {
        button.classList.toggle('is-selected', button.getAttribute('data-order-type') === state.orderType);
      });

      Array.prototype.slice.call(app.querySelectorAll('[data-payment-mode]')).forEach(function (button) {
        button.classList.toggle('is-selected', button.getAttribute('data-payment-mode') === state.paymentMode);
      });

      itemCards.forEach(function (card) {
        var itemId = card.getAttribute('data-menu-item-id');
        var isSelected = !!state.items[itemId];
        card.classList.toggle('is-selected', isSelected);

        var button = card.querySelector('[data-select-item]');
        if (button && !card.classList.contains('is-disabled')) {
          button.textContent = isSelected ? getText('updateItem', 'Update selection') : getText('selectItem', 'Add to order');
        }
      });

      if (addressWrap) {
        addressWrap.hidden = state.orderType !== 'delivery';
      }

      if (providerWrap) {
        providerWrap.hidden = state.orderType !== 'delivery';
      }

      Array.prototype.slice.call(app.querySelectorAll('[data-order-warning]')).forEach(function (warningBox) {
        warningBox.hidden = warningBox.getAttribute('data-order-warning') !== state.orderType;
      });

      if (paymentWarning) {
        paymentWarning.hidden = state.paymentMode !== 'cash';
      }

      if (providerSelect) {
        providerSelect.value = state.deliveryProvider || '';
      }

      renderLocalSummary();
    }

    function validateStep(step) {
      if (step === 1 && !state.bookingDayId) {
        throw new Error(getText('missingDate', 'Please choose a date first.'));
      }

      if (step === 2 && !getSelectedItemsPayload().length) {
        throw new Error(getText('missingItem', 'Please add at least one menu item.'));
      }

      if (step === 3 && !state.slotTime) {
        throw new Error(getText('missingSlot', 'Please choose a time slot.'));
      }
      if (step === 3 && settings.hasTableLayout && !state.tableId) {
        throw new Error(getText('missingTable', 'Please choose a table.'));
      }

      if (step === 4) {
        if (!state.slotTime) {
          throw new Error(getText('missingSlot', 'Please choose a time slot.'));
        }
        if (!state.orderType) {
          throw new Error(getText('missingOrderType', 'Please choose an order type.'));
        }
        if (!state.paymentMode) {
          throw new Error(getText('missingPaymentMode', 'Please choose a payment option.'));
        }
        if (state.orderType === 'delivery' && !state.deliveryProvider) {
          throw new Error(getText('missingDeliveryProvider', 'Please choose a delivery provider.'));
        }
      }

      if (step === 5) {
        var nameInput = app.querySelector('[data-customer-field="name"]');
        var phoneInput = app.querySelector('[data-customer-field="phone"]');
        var addressInput = app.querySelector('[data-customer-field="address"]');

        if ((fieldSettings.name && fieldSettings.name.required) && (!nameInput || !String(nameInput.value || '').trim())) {
          throw new Error(getText('missingName', 'Please enter your name.'));
        }

        if ((fieldSettings.phone && fieldSettings.phone.required) && (!phoneInput || !String(phoneInput.value || '').trim())) {
          throw new Error(getText('missingPhone', 'Please enter your phone number.'));
        }

        if (state.orderType === 'delivery' && (!addressInput || !String(addressInput.value || '').trim())) {
          throw new Error(getText('missingAddress', 'Please enter the delivery address.'));
        }
      }
    }

    function showMessage(message, isError) {
      if (!finalMessage) {
        return;
      }

      if (!message) {
        finalMessage.textContent = '';
        finalMessage.classList.remove('is-visible', 'is-error');
        return;
      }

      finalMessage.textContent = message;
      finalMessage.classList.toggle('is-error', !!isError);
      finalMessage.classList.add('is-visible');
    }

    function buildPayload() {
      return {
        booking_day_id: state.bookingDayId,
        booking_date: state.bookingDate,
        slot_time: state.slotTime,
        table_id: state.tableId,
        order_type: state.orderType,
        payment_mode: state.paymentMode,
        delivery_provider: state.deliveryProvider,
        guests: Math.max(1, toNumber(state.guests, 1)),
        items: getSelectedItemsPayload(),
        customer: state.customer
      };
    }

    function fetchSlots() {
      if (!state.bookingDayId || !slotResults) {
        return Promise.resolve();
      }

      var needs = getCapacityNeeds();
      return request('goody_reservation_slots', {
        booking_day_id: state.bookingDayId,
        booking_date: state.bookingDate,
        order_type: state.orderType || '',
        selected_time: state.slotTime || '',
        person_need: String(needs.personNeed),
        kg_need: String(needs.kgNeed)
      }).then(function (data) {
        slotResults.innerHTML = data.html || '';

        var selectedButton = slotResults.querySelector('[data-slot="' + state.slotTime + '"]');
        if (!selectedButton || selectedButton.disabled) {
          state.slotTime = '';
          state.tableId = '';
          state.tableLabel = '';
          state.tableLocation = '';
        }

        syncOrderTypesBySelectedSlot();
        renderLocalSummary();
        if (state.slotTime) {
          fetchTables();
        } else if (tableResults) {
          tableResults.innerHTML = '<div class="goody-inline-empty">' + getText('missingSlot', 'Please choose a time slot.') + '</div>';
        }
      }).catch(function (error) {
        slotResults.innerHTML = '<div class="goody-inline-empty">' + error.message + '</div>';
      });
    }

    function fetchTables() {
      if (!tableResults) {
        return Promise.resolve();
      }
      if (!settings.hasTableLayout) {
        tableResults.innerHTML = '';
        return Promise.resolve();
      }
      if (!state.bookingDayId || !state.bookingDate || !state.slotTime) {
        tableResults.innerHTML = '<div class="goody-inline-empty">' + getText('missingSlot', 'Please choose a time slot.') + '</div>';
        return Promise.resolve();
      }

      return request('goody_reservation_tables', {
        booking_day_id: state.bookingDayId,
        booking_date: state.bookingDate,
        slot_time: state.slotTime,
        guests: String(Math.max(1, toNumber(state.guests, 1))),
        selected_table_id: state.tableId || ''
      }).then(function (data) {
        tableResults.innerHTML = data.html || '';
        if (state.tableId) {
          var selectedTableButton = tableResults.querySelector('[data-table-id="' + state.tableId + '"]');
          if (!selectedTableButton || selectedTableButton.disabled) {
            state.tableId = '';
            state.tableLabel = '';
            state.tableLocation = '';
          }
        }
        renderLocalSummary();
      }).catch(function (error) {
        tableResults.innerHTML = '<div class="goody-inline-empty">' + error.message + '</div>';
      });
    }

    function requestQuote() {
      lastQuoteHtml = '';
      renderLocalSummary();

      return request('goody_reservation_quote', {
        payload: JSON.stringify(buildPayload())
      }).then(function (data) {
        lastQuoteHtml = data.summary_html || '';
        if (finalSummary) {
          finalSummary.innerHTML = lastQuoteHtml;
        }
        renderLocalSummary();
        return data;
      });
    }

    function bindDateSelection() {
      Array.prototype.slice.call(app.querySelectorAll('[data-booking-day]')).forEach(function (button) {
        if (button.disabled) {
          return;
        }

        button.addEventListener('click', function () {
          state.bookingDayId = button.getAttribute('data-booking-day') || '';
          state.bookingDate = button.getAttribute('data-booking-date') || '';
          state.slotTime = '';
          state.tableId = '';
          state.tableLabel = '';
          state.tableLocation = '';
          syncSelectionUI();
          fetchSlots();
        });
      });
    }

    function bindMenuFilters() {
      initMenuFilterApp(app.querySelector('[data-goody-menu-filter-app]') || app);
    }

    function bindMenuSelection() {
      itemCards.forEach(function (card) {
        var itemId = String(card.getAttribute('data-menu-item-id') || '');
        var selectButton = card.querySelector('[data-select-item]');
        if (!selectButton) {
          return;
        }

        selectButton.addEventListener('click', function () {
          var qtyInput = card.querySelector('[data-qty-for="' + itemId + '"]');
          var addons = Array.prototype.slice.call(card.querySelectorAll('[data-addon-for="' + itemId + '"]:checked')).map(function (input) {
            return input.value;
          });
          var qty = qtyInput ? toNumber(qtyInput.value, 0) : 0;

          if (qty <= 0) {
            delete state.items[itemId];
          } else {
            state.items[itemId] = {
              id: Number(itemId),
              qty: qty,
              addons: addons
            };
          }

          lastQuoteHtml = '';
          syncSelectionUI();
          if (state.bookingDayId) {
            fetchSlots();
          }
        });
      });
    }

    function bindChoiceCards() {
      Array.prototype.slice.call(app.querySelectorAll('[data-order-type]')).forEach(function (button) {
        button.addEventListener('click', function () {
          state.orderType = button.getAttribute('data-order-type') || orderTypeKeys[0] || 'dine_in';
          lastQuoteHtml = '';
          syncSelectionUI();
          fetchSlots();
        });
      });

      Array.prototype.slice.call(app.querySelectorAll('[data-payment-mode]')).forEach(function (button) {
        button.addEventListener('click', function () {
          state.paymentMode = button.getAttribute('data-payment-mode') || paymentModeKeys[0] || 'full';
          lastQuoteHtml = '';
          syncSelectionUI();
        });
      });

      if (providerSelect) {
        providerSelect.addEventListener('change', function () {
          state.deliveryProvider = String(providerSelect.value || '');
          lastQuoteHtml = '';
          syncSelectionUI();
        });
      }
    }

    function bindCustomerFields() {
      Array.prototype.slice.call(app.querySelectorAll('[data-customer-field]')).forEach(function (field) {
        field.addEventListener('input', function () {
          var key = field.getAttribute('data-customer-field');
          var value = field.value || '';

          if (key === 'guests') {
            state.guests = Math.max(1, toNumber(value, 1));
            lastQuoteHtml = '';
            if (state.bookingDayId) {
              fetchSlots();
              if (state.slotTime) {
                fetchTables();
              }
            } else {
              renderLocalSummary();
            }
            return;
          }

          state.customer[key] = value;
          renderLocalSummary();
        });
      });
    }

    function bindSlotSelection() {
      if (!slotResults) {
        return;
      }

      slotResults.addEventListener('click', function (event) {
        var button = event.target.closest('[data-slot]');
        if (!button || button.disabled) {
          return;
        }

        state.slotTime = button.getAttribute('data-slot') || '';
        state.tableId = '';
        state.tableLabel = '';
        state.tableLocation = '';
        lastQuoteHtml = '';

        Array.prototype.slice.call(slotResults.querySelectorAll('[data-slot]')).forEach(function (slotButton) {
          slotButton.classList.toggle('is-selected', slotButton === button);
        });

        syncOrderTypesBySelectedSlot();
        syncSelectionUI();
        fetchTables();
      });
    }

    function bindTableSelection() {
      if (!tableResults) {
        return;
      }

      tableResults.addEventListener('click', function (event) {
        var button = event.target.closest('[data-table-id]');
        if (!button || button.disabled) {
          return;
        }

        state.tableId = String(button.getAttribute('data-table-id') || '');
        state.tableLabel = String(button.getAttribute('data-table-label') || '').trim();
        state.tableLocation = String(button.getAttribute('data-table-location') || '').trim();
        lastQuoteHtml = '';

        Array.prototype.slice.call(tableResults.querySelectorAll('[data-table-id]')).forEach(function (tableButton) {
          tableButton.classList.toggle('is-selected', tableButton === button);
        });

        renderLocalSummary();
      });
    }

    function bindStepButtons() {
      Array.prototype.slice.call(app.querySelectorAll('[data-next-step]')).forEach(function (button) {
        button.addEventListener('click', function () {
          var currentStep = state.step;
          var nextStep = Number(button.getAttribute('data-next-step'));

          try {
            if (nextStep === 6) {
              validateFlowBeforeQuote(validateStep);
            } else {
              validateStep(currentStep);
            }
          } catch (error) {
            showMessage(error.message, true);
            return;
          }

          if (nextStep === 6) {
            requestQuote().then(function () {
              showMessage('', false);
              setStep(nextStep);
            }).catch(function (error) {
              showMessage(error.message || getText('errorMessage', 'Please review the form and try again.'), true);
            });
            return;
          }

          if (nextStep === 3) {
            setStep(nextStep);
            fetchSlots().then(function () {
              showMessage('', false);
            });
            return;
          }

          if (nextStep === 4) {
            syncOrderTypesBySelectedSlot();
          }

          showMessage('', false);
          setStep(nextStep);
        });
      });

      Array.prototype.slice.call(app.querySelectorAll('[data-prev-step]')).forEach(function (button) {
        button.addEventListener('click', function () {
          setStep(Number(button.getAttribute('data-prev-step')));
          showMessage('', false);
        });
      });
    }

    function bindSubmit() {
      var submitButton = app.querySelector('[data-submit-booking]');
      if (!submitButton) {
        return;
      }

      submitButton.addEventListener('click', function () {
        request('goody_reservation_submit', {
          payload: JSON.stringify(buildPayload())
        }).then(function (data) {
          showMessage(data.message || 'Reservation created successfully.', false);
          submitButton.disabled = true;
          window.setTimeout(function () {
            window.location.href = data.redirect_url;
          }, 600);
        }).catch(function (error) {
          showMessage(error.message || getText('errorMessage', 'Please review the form and try again.'), true);
        });
      });
    }

    bindDateSelection();
    bindMenuFilters();
    bindMenuSelection();
    bindChoiceCards();
    bindCustomerFields();
    bindSlotSelection();
    bindTableSelection();
    bindStepButtons();
    bindSubmit();
    syncSelectionUI();
    setStep(1);

    // Auto-pick the first available date so booking can start immediately.
    var firstAvailableDateButton = app.querySelector('[data-booking-day]:not([disabled])');
    if (firstAvailableDateButton) {
      state.bookingDayId = firstAvailableDateButton.getAttribute('data-booking-day') || '';
      state.bookingDate = firstAvailableDateButton.getAttribute('data-booking-date') || '';
      syncSelectionUI();
      fetchSlots();
    }
  }

  function initStatusApp(app) {
    var result = app.querySelector('[data-status-result]');
    var form = app.querySelector('[data-status-form]');
    if (!result || !form) {
      return;
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      var formData = new FormData(form);

      request('goody_reservation_status_lookup', {
        reference: String(formData.get('reference') || '').trim(),
        phone: String(formData.get('phone') || '').trim()
      }).then(function (data) {
        var trackingHtml = data.tracking_html || '';
        if (!trackingHtml) {
          trackingHtml = '<div class="goody-status-result__state"><strong>Current status:</strong> ' + data.status_label + '</div>';
        }
        result.innerHTML = trackingHtml + (data.summary_html || '');
      }).catch(function (error) {
        result.innerHTML = '<div class="goody-inline-empty">' + error.message + '</div>';
      });
    });
  }

  Array.prototype.slice.call(document.querySelectorAll('[data-goody-reservation-app]')).forEach(initReservationApp);
  Array.prototype.slice.call(document.querySelectorAll('[data-goody-menu-filter-app]')).forEach(initMenuFilterApp);
  Array.prototype.slice.call(document.querySelectorAll('[data-goody-status-app]')).forEach(initStatusApp);
});
