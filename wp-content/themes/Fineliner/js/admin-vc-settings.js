/*global jQuery:false */

jQuery(document).ready(function($) {
	"use strict";

	// Hide "Disable responsive content elements" and "Google font subsets" options since they cannot be used with the theme
	$('.form-table tr').each(function() {

		if ( $(this).text().toLowerCase().indexOf('disable responsive') > -1 ) {
			 //$(this).text().toLowerCase().indexOf('google fonts') > -1 ) {
			$(this).css('display', 'none');
		};

	});

});