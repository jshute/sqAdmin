$("document").ready(function() {
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
});