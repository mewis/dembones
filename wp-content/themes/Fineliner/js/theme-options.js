/*global jQuery:false, ThemeOptions:false */

jQuery(document).ready(function($) {"use strict";

	// ---------------------------------------------- //
	// General
	// ---------------------------------------------- //
	
	$('#option-tree-options-layouts-form').append('<a href="javascript:;" id="uxb-layout-hint" class="tip" data-tip=\'' + ThemeOptions.layout_hint_desc + '\'>' + ThemeOptions.layout_hint + '</a>');

	$('.tip').tipr();

}); 