/*global jQuery:false */

jQuery(document).ready(function($) {
	"use strict";

	// Set active state
	//$('#tab_import').addClass('ui-tabs-active ui-state-active');
	$('.toplevel_page_ot-settings #option-tree-settings-api #section_layouts .format-setting.type-textarea.has-desc').append( '<div class="uxb-description description">' + CustomOT.layouts_desc + '</div>' );

});