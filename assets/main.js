$(function() {
	function checkInput(type) {
		var input = document.createElement('input');
		input.setAttribute('type', type);
		return input.type === type;
	}

	var $cache = {
		picker: $('.sq-picker')
	};

	$("body").removeClass("preload");

	$("#show-nav").click(function(event) {
		event.preventDefault();
		$("body").toggleClass("open");
	});

	if (checkInput('date')) {
		$('input[type=date]').each(function() {
			$(this).val($(this).data('date'));
		});
	}

	$('.sq-rich-editor').trumbowyg({
		btns: [
			['viewHTML'],
        	['undo', 'redo'],
        	['formatting'],
        	['strong', 'em'],
        	['link'],
        	['unorderedList', 'orderedList'],
        	['horizontalRule'],
        	['removeformat'],
		],
		autogrow: true
	});

	var active = false;

	$('[id*="name"]').on('keyup', function() {
		var $url = $('[id*="url"]');
		if ($url.val() === '' && !active) {
			active = true;
		}

		if (active) {
			$url.val($(this).val().toLowerCase().replace(/\s+/g, '-'));
		}
	});

	$('.sq-picker').each(function() {
		var selectedUrl = $(this).find('.sq-picker-input').val();
		if (selectedUrl) {
			$(this).find('img[data-src*="' + selectedUrl + '"]').addClass('is-selected');
		}
	});

	$('button.sq-toggle').on('click', function(e) {
		e.preventDefault();
		$(this).toggleClass('is-open').toggleClass('is-closed');
		$(this).parent('.sq-picker').toggleClass('is-open');
	});

	// Picks the overal folder not just the image
	$('.sq-pick').on('click', function(e) {
		e.preventDefault();
		$(this).parents('.sq-picker').children('input').val(
			$(this).parents('.sq-picker').find('.sq-picker-path').data('path') || 'uploads'
		);
	});

	$('.sq-picker-files').on('click', 'img', function(e) {
		e.preventDefault();
		$(this).parents('.sq-picker').find('img').removeClass('is-selected');
		$(this).addClass('is-selected');
		$(this).parents('.sq-picker').children('input').val($(this).data('src').replace(sq.data.base, ''));
	});

	$cache.picker.on('click', '.sq-picker-folder', function(e) {
		e.preventDefault();
		$(this).parents('.sq-grid').load($(this).attr('href') + '&sqContext=grid-content');
	});

	$('.sq-picker-clear').on('click', function(e) {
		e.preventDefault();
		$(this).parents('.sq-picker').children('input').val('');
		$(this).parents('.sq-picker').find('img').removeClass('is-selected');
	});

	var $selectedType = $('select[id*=type] option:selected');
	$('select[id*=type]').on('change', function() {
		var confirmText = 'Warning: Changing the page type will discard unsaved changes. Do you want to continue?';
		if (confirm(confirmText)) {
			location = location.pathname.split('?')[0] + '?type=' + $(this).val();
		} else {
			$selectedType.prop('selected', true);
		}
	});

	$('input[type=hidden].sq-picker-input').each(function() {
		var IDs = $(this).val().split(',');
		$.each(IDs, function(index, value) {
			$('.sq-picker [id$="' + value + '"]').prop('checked', true);
		});
	});

	$('.sq-picker table tr').on('click', function() {
		var value = $(this).children('td:first-child').text();
		$(this).parents('.sq-picker').find('.sq-picker-input').val(value);
	});

	$('form').on('submit', function() {
		$('.sq-picker').each(function() {
			var IDs = [];
			$(this).find('input[type=checkbox]:checked').each(function() {
				IDs.push($(this).attr('name').replace(/\D+/g, ''));
			});

			if (IDs.length) {
				$(this).find('.sq-picker-input').val(IDs.join(','));
			}
		});
	});

	$('.sq-form-heading.sq-toggle').on('click', function() {
		$(this).toggleClass('is-closed').toggleClass('is-open');
	});

	// @TODO Generalize this so it isn't hardcoded
	if ($('.sq-galleries-form-page #sq-context-grid-content').length) {
		sortable('.sq-galleries-form-page #sq-context-grid-content', {
			items: '.sq-grid-item',
			placeholderClass: 'sq-grid-placeholder'
		})[0].addEventListener('sortupdate', function() {
			var ids = $('.sq-grid-item').map(function() {
				return $(this).data('id');
			}).get();
			$('#sort-keys').val(ids.reverse().join());
		});
	}
});