$(function() {
	var $cache = {
		picker: $('.sq-picker')
	};
	
	$("body").removeClass("preload");
	
	$("#show-nav").click(function(event) {
		event.preventDefault();
		$("body").toggleClass("open");
	});
	
	$("textarea.richtext").tinymce({
		script_url: tinymcePath + "/tinymce.min.js",
		statusbar: false,
		body_id: "mce-content",
		content_css: "../../assets/admin/main.css?" + new Date().getTime(),
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste"
		],
		
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
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
			$(this).find('img[src*="' + selectedUrl + '"]').addClass('is-selected');
		}
	});
	
	$('.sq-picker-toggle').on('click', function(e) {
		e.preventDefault();
		$(this).parent('.sq-picker').toggleClass('is-open');
	});
	
	$cache.picker.on('click', 'img', function(e) {
		e.preventDefault();
		$(this).parents('.sq-picker').find('img').removeClass('is-selected');
		$(this).addClass('is-selected');
		$(this).parents('.sq-picker').children('input').val($(this).attr('src').replace(sq.data.base, ''));
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
	
	$('.sq-picker-input').each(function() {
		var IDs = $(this).val().split(',');
		$.each(IDs, function(index, value) {
			$('[id*="' + value + '"]').prop('checked', true);
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
});