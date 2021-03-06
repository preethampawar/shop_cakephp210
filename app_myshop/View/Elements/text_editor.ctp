<?php //echo $this->Html->script('tinymce/jscripts/tiny_mce/jquery.tinymce'); ?>
<!--<script type="text/javascript">-->
<!--	$().ready(function () {-->
<!--		$('textarea.tinymce').tinymce({-->
<!--			// Location of TinyMCE script-->
<!--			script_url: '/js/tinymce/jscripts/tiny_mce/tiny_mce.js',-->
<!---->
<!--			// General options-->
<!--			theme: "advanced",-->
<!--			//plugins :  "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",-->
<!---->
<!--			plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advlink,iespell,preview,directionality,fullscreen,noneditable",-->
<!---->
<!--			// Theme options-->
<!--			theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",-->
<!---->
<!--			//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",-->
<!--			theme_advanced_buttons2: "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",-->
<!--			theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",-->
<!--			//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",-->
<!--			theme_advanced_toolbar_location: "top",-->
<!--			theme_advanced_toolbar_align: "left",-->
<!--			theme_advanced_statusbar_location: "bottom",-->
<!--			theme_advanced_resizing: true,-->
<!---->
<!--			// Example content CSS (should be your site CSS)-->
<!--			content_css: "/css/styles/layout.css",-->
<!---->
<!--			// Drop lists for link/image/media/template dialogs-->
<!--			// template_external_list_url : "lists/template_list.js",-->
<!--			// external_link_list_url : "lists/link_list.js",-->
<!--			// external_image_list_url : "lists/image_list.js",-->
<!--			// media_external_list_url : "lists/media_list.js",-->
<!---->
<!--			// Replace values for the template plugin-->
<!--			template_replace_values: {-->
<!--				username: "Some User",-->
<!--				staffid: "991234"-->
<!--			}-->
<!--		});-->
<!--	});-->
<!--</script>-->


<link rel="stylesheet" href="/vendor/summernote-0.8.18-dist/summernote-lite.min.css">
<script src="/vendor/summernote-0.8.18-dist/summernote-lite.min.js"></script>

<!--<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">-->
<!--<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>-->
<script>
	$(document).ready(function () {
		$('textarea.tinymce').summernote({
			placeholder: 'Enter text here',
			tabsize: 2,
			height: 200,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'clear']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'video', 'picture']],				
				['view', ['fullscreen', 'codeview', 'help']]
			]
		});
	})
</script>
