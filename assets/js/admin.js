jQuery(function ($) {
  const tabWrapper = $('.goody-tab-wrapper');
  if (tabWrapper.length) {
    tabWrapper.on('click', '.nav-tab', function (e) {
      e.preventDefault();
      const target = $(this).attr('href');

      tabWrapper.find('.nav-tab').removeClass('nav-tab-active');
      $(this).addClass('nav-tab-active');

      $('.goody-tab-panel').hide();
      $(target).show();
    });
  }

  function getControllerValue(fieldId) {
    const input = $('#' + fieldId);
    if (!input.length) return '';

    if (input.is(':checkbox')) {
      return input.is(':checked') ? '1' : '0';
    }

    return String(input.val() || '').trim();
  }

  function updateConditionalRows() {
    $('.goody-option-row[data-depends-on]').each(function () {
      const row = $(this);
      const dependsOn = String(row.attr('data-depends-on') || '').trim();
      const dependsValue = String(row.attr('data-depends-value') || '').trim();

      if (!dependsOn) {
        row.removeClass('goody-is-hidden').show();
        return;
      }

      const actualValue = getControllerValue(dependsOn);
      const shouldShow = dependsValue === '' ? actualValue !== '' : actualValue === dependsValue;

      row.toggleClass('goody-is-hidden', !shouldShow);
      row.toggle(shouldShow);
    });
  }

  function setFieldValidationState(input, isValid, message) {
    const row = input.closest('.goody-option-row');
    const fieldId = input.attr('id') || input.attr('name') || 'goody-field';
    let note = row.find('.goody-validation-note[data-for="' + fieldId + '"]');

    if (!note.length) {
      note = $('<p class="description goody-validation-note" data-for="' + fieldId + '"></p>');
      row.find('td').append(note);
    }

    row.toggleClass('goody-field-invalid', !isValid);
    if (isValid) {
      note.hide().text('');
    } else {
      note.text(message).show();
    }
  }

  function isLikelyHttpUrl(value) {
    if (!value) return false;

    try {
      const parsed = new URL(value);
      return parsed.protocol === 'http:' || parsed.protocol === 'https:';
    } catch (error) {
      return false;
    }
  }

  function validateGoodyInput(input) {
    const validationType = String(input.attr('data-goody-validate') || '').trim();
    if (!validationType) return true;

    const value = String(input.val() || '').trim();

    if (validationType === 'url_optional') {
      if (value === '') {
        setFieldValidationState(input, true, '');
        return true;
      }

      const valid = isLikelyHttpUrl(value);
      setFieldValidationState(
        input,
        valid,
        valid ? '' : 'Please enter a valid URL with http:// or https://'
      );
      // Keep save flow unblocked for optional URL fields; backend sanitizer will normalize/empty invalid URLs.
      return true;
    }

    if (validationType === 'number_range') {
      if (value === '') {
        setFieldValidationState(input, true, '');
        return true;
      }

      const numberValue = Number(value);
      const min = input.attr('min') !== undefined && input.attr('min') !== '' ? Number(input.attr('min')) : null;
      const max = input.attr('max') !== undefined && input.attr('max') !== '' ? Number(input.attr('max')) : null;
      let valid = !Number.isNaN(numberValue);
      let message = 'Please enter a valid number.';

      if (valid && min !== null && numberValue < min) {
        valid = false;
        message = 'Value must be at least ' + min + '.';
      }

      if (valid && max !== null && numberValue > max) {
        valid = false;
        message = 'Value must be at most ' + max + '.';
      }

      setFieldValidationState(input, valid, valid ? '' : message);
      return valid;
    }

    return true;
  }

  function openMediaFrame(options) {
    const frame = wp.media({
      title: options.title || 'Select Media',
      button: { text: options.buttonText || 'Use selected media' },
      multiple: options.multiple || false,
      library: options.library || {}
    });

    frame.on('select', function () {
      options.onSelect(frame.state().get('selection'));
    });

    frame.open();
  }

  $(document).on('click', '.goody-media-upload', function (e) {
    e.preventDefault();
    const button = $(this);
    const target = $('#' + button.data('target'));
    let preview = button.siblings('.goody-media-preview');
    if (!preview.length) {
      preview = button.closest('.goody-term-media-actions').siblings('.goody-media-preview').first();
    }
    const libraryType = String(button.data('libraryType') || '').trim();
    const library = {};

    if (libraryType) {
      library.type = libraryType.split(',').map(function (type) {
        return String(type || '').trim();
      }).filter(Boolean);
    } else {
      library.type = ['image', 'video'];
    }

    openMediaFrame({
      title: button.data('frameTitle') || 'Select Media',
      buttonText: button.data('buttonText') || 'Use selected media',
      multiple: false,
      library: library,
      onSelect: function (selection) {
        const attachment = selection.first().toJSON();
        target.val(attachment.id);

        if (attachment.type === 'video') {
          preview.html('<p><strong>Video selected:</strong> ' + attachment.filename + '</p>');
        } else {
          preview.html('<img src="' + attachment.url + '" style="max-width:220px;height:auto;border-radius:8px;" alt="">');
        }
      }
    });
  });

  $(document).on('click', '.goody-media-clear', function (e) {
    e.preventDefault();
    const button = $(this);
    const target = $('#' + button.data('target'));
    let preview = button.siblings('.goody-media-preview');
    if (!preview.length) {
      preview = button.closest('.goody-term-media-actions').siblings('.goody-media-preview').first();
    }

    target.val('');
    preview.html('');
  });

  $(document).on('click', '.goody-gallery-upload', function (e) {
    e.preventDefault();
    const button = $(this);
    const target = $('#' + button.data('target'));
    const preview = button.siblings('.goody-gallery-preview');

    openMediaFrame({
      multiple: true,
      library: { type: ['image'] },
      title: 'Select Images',
      buttonText: 'Use images',
      onSelect: function (selection) {
        const ids = [];
        const thumbs = [];

        selection.each(function (item) {
          const attachment = item.toJSON();
          ids.push(attachment.id);
          thumbs.push(
            '<span class="goody-gallery-thumb" data-id="' + attachment.id + '" style="position:relative;display:inline-flex;">' +
              '<img src="' + attachment.url + '" style="width:48px;height:48px;object-fit:cover;border-radius:6px;" alt="">' +
              '<button type="button" class="goody-gallery-thumb-remove" aria-label="Remove image" title="Remove image" style="position:absolute;top:-7px;right:-7px;border:0;border-radius:999px;width:18px;height:18px;line-height:18px;padding:0;background:#b32d2e;color:#fff;cursor:pointer;">&times;</button>' +
            '</span>'
          );
        });

        target.val(ids.join(','));
        preview.html(thumbs.join(''));
      }
    });
  });

  $(document).on('click', '.goody-gallery-clear', function (e) {
    e.preventDefault();
    const button = $(this);
    const target = $('#' + button.data('target'));
    const preview = button.siblings('.goody-gallery-preview');

    target.val('');
    preview.html('');
  });

  $(document).on('click', '.goody-gallery-thumb-remove', function (e) {
    e.preventDefault();
    const thumb = $(this).closest('.goody-gallery-thumb');
    const preview = thumb.closest('.goody-gallery-preview');
    const hidden = preview.siblings('.goody-gallery-field').first();

    thumb.remove();

    const keptIds = [];
    preview.find('.goody-gallery-thumb').each(function () {
      const id = String($(this).attr('data-id') || '').trim();
      if (id) keptIds.push(id);
    });

    hidden.val(keptIds.join(','));
  });

  function updateRepeaterValue(repeater) {
    const rows = [];
    repeater.find('.goody-repeater-row').each(function () {
      const row = {};
      $(this).find('[data-field]').each(function () {
        const input = $(this);
        if (input.is(':checkbox')) {
          row[input.data('field')] = input.is(':checked') ? '1' : '0';
          return;
        }
        row[input.data('field')] = input.val();
      });
      rows.push(row);
    });

    const target = $('#' + repeater.data('target'));
    target.val(JSON.stringify(rows));
  }

  function renderBookingSlotOrderTypeChecks() {
    const types = [
      { key: 'dine_in', label: 'Dine In' },
      { key: 'pickup', label: 'Pickup' },
      { key: 'delivery', label: 'Delivery' }
    ];

    return (
      '<div style="display:flex;flex-wrap:wrap;gap:8px 12px;">' +
      types.map(function (type) {
        return '<label style="display:inline-flex;align-items:center;">' +
          '<input type="checkbox" data-slot-order-type data-order-type-key="' + type.key + '" checked>' +
          '<span style="margin-left:4px;">' + type.label + '</span>' +
        '</label>';
      }).join('') +
      '</div>' +
      '<input type="hidden" data-field="order_types" value="">'
    );
  }

  function renderRepeaterRow(columns, target) {
    let html = '<div class="goody-repeater-row">';

    columns.forEach(function (column) {
      if (target === 'goody_booking_slots' && column.key === 'order_types') {
        html += renderBookingSlotOrderTypeChecks();
        return;
      }

      let type = 'text';
      if (column.type === 'time') type = 'time';
      if (column.type === 'checkbox') {
        const checkedAttr = column.key === 'enabled' ? '' : ' checked';
        html += '<label class="goody-repeater-checkbox"><input type="checkbox" data-field="' + column.key + '" value="1"' + checkedAttr + '> ' + column.label + '</label>';
        return;
      }

      html += '<input type="' + type + '" data-field="' + column.key + '" placeholder="' + column.label + '" value="">';
    });

    html += '<button type="button" class="button goody-row-up">↑</button>';
    html += '<button type="button" class="button goody-row-down">↓</button>';
    html += '<button type="button" class="button goody-row-remove">Remove</button>';
    html += '</div>';

    return html;
  }

  $(document).on('click', '.goody-repeater-add', function () {
    const target = $(this).data('target');
    const repeater = $('.goody-repeater[data-target="' + target + '"]');
    const columns = JSON.parse(repeater.attr('data-columns') || '[]');

    repeater.append(renderRepeaterRow(columns, String(target || '')));
    updateRepeaterValue(repeater);
  });

  $(document).on('click', '.goody-row-remove', function () {
    const repeater = $(this).closest('.goody-repeater');
    $(this).closest('.goody-repeater-row').remove();
    updateRepeaterValue(repeater);
  });

  $(document).on('click', '.goody-row-up', function () {
    const repeater = $(this).closest('.goody-repeater');
    const row = $(this).closest('.goody-repeater-row');
    row.prev('.goody-repeater-row').before(row);
    updateRepeaterValue(repeater);
  });

  $(document).on('click', '.goody-row-down', function () {
    const repeater = $(this).closest('.goody-repeater');
    const row = $(this).closest('.goody-repeater-row');
    row.next('.goody-repeater-row').after(row);
    updateRepeaterValue(repeater);
  });

  $(document).on('change input', '.goody-repeater [data-field]', function () {
    updateRepeaterValue($(this).closest('.goody-repeater'));
  });

  $('.goody-repeater').each(function () {
    updateRepeaterValue($(this));
  });

  $(document).on('change', 'input, select, textarea', function () {
    updateConditionalRows();
  });

  $(document).on('input blur change', '[data-goody-validate]', function () {
    const input = $(this);
    if (!input.is(':visible')) return;
    validateGoodyInput(input);
  });

  $('form').on('submit', function (e) {
    let hasError = false;

    $(this)
      .find('[data-goody-validate]:visible')
      .each(function () {
        if (!validateGoodyInput($(this))) {
          hasError = true;
        }
      });

    if (hasError) {
      e.preventDefault();
      const firstErrorField = $(this).find('.goody-field-invalid:visible').first();
      if (firstErrorField.length) {
        $('html, body').animate({ scrollTop: firstErrorField.offset().top - 120 }, 160);
      }
    }
  });

  updateConditionalRows();
  $('[data-goody-validate]').each(function () {
    const input = $(this);
    if (!input.is(':visible')) return;
    validateGoodyInput(input);
  });
});
