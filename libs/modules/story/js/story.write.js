(function($) {
	$(document).ready(function() {

		var options = {
			'button': {
				'preview' : false,
				'fullscreen' : false,
				'edit' : false
			},
			'theme':{
				'base' : 'http://local.chrncl.me/libs/modules/story/epiceditor/themes/base/epiceditor.css',
				'preview' : 'http://local.chrncl.me/libs/modules/story/epiceditor/themes/preview/github.css',
				'editor' : 'http://local.chrncl.me/libs/modules/story/epiceditor/themes/editor/epic-light.css'
			}


		}

		function passContent(src, target){
			content = src.exportFile();
			target.importFile('content', content);
			target.preview();
		}

		var editor = new EpicEditor(options)
		options.container = 'epiceditorpreview';

		var preview = new EpicEditor(options).load();
		preview.preview();
		
		
		editor.on('update', function(){
			content = editor.exportFile();
			preview.importFile('content', content);
			preview.preview();
			$('#story-content').val(content);
		});
		editor.on('load', function(){
			editor.importFile('content', $('#story-content').val());
			content = editor.exportFile();
			preview.importFile('content', content);
			preview.preview();
			//$('#story-content').val(content);
		});
		editor.load();
		

		

	})
})(jQuery);
