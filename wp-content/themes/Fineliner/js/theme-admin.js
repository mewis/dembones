/*global jQuery:false, AdminSettings:false */

jQuery(document).ready(function($) {
	"use strict";
	
	$('#simplepagesidebarsdiv .inside > p').first().after('<p style="margin-top: 35px;"><em>' + AdminSettings.sidebar_text + '</em></p>');
	
	$('#uxbarn_page_sidebar_appearance').closest('.format-setting.type-select.no-desc').after('<p style="margin-top: 10px;"><em>' + AdminSettings.sidebar_appearance_text + '</em></p>');
	
});
