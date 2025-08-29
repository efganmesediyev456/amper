/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
/*
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'az';
	// config.uiColor = '#AADC6E';
// ...
   config.filebrowserBrowseUrl = 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
   config.filebrowserImageBrowseUrl = 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
   config.filebrowserFlashBrowseUrl = 'ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
   config.filebrowserUploadUrl = 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
   config.filebrowserImageUploadUrl = 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
   config.filebrowserFlashUploadUrl = 'ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
// ...	
};
*/

CKEDITOR.editorConfig = function( config ) {
   config.filebrowserBrowseUrl      = 'assets/ckeditor/filemanager/dialog.php?type=2&editor=ckeditor&fldr=';
	config.filebrowserUploadUrl      = 'assets/ckeditor/filemanager/dialog.php?type=2&editor=ckeditor&fldr=';
	config.filebrowserImageBrowseUrl = 'assets/ckeditor/filemanager/dialog.php?type=1&editor=ckeditor&fldr=';
	config.filebrowserUploadUrl      = 'assets/ckeditor/filemanager/upload.php?opener=ckeditor&type=files';
   config.filebrowserImageUploadUrl = 'assets/ckeditor/filemanager/upload.php?opener=ckeditor&type=images';
};
