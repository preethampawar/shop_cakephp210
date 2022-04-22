<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
	$('textarea.texteditor').summernote({
		placeholder: 'Enter text here',
		tabsize: 2,
		height: 150,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'underline', 'clear']],
			// ['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			// ['table', ['table']],
			['insert', ['link', 'picture', 'video']],
			// ['view', ['fullscreen', 'codeview', 'help']]
		]
	});
</script>
