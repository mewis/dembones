/*global jQuery:false, ThemeOptions:false, google:false */

/*
 * Fineliner - Responsive Portfolio WordPress Theme
 * By UXbarn
 * Themeforest Profile: http://themeforest.net/user/UXbarn?ref=UXbarn
 * Demo URL: http://themes.uxbarn.com/redirect.php?theme=fineliner_wp
 */

jQuery(document).ready(function($) {
	"use strict";

	// --------------------------------------------------------- //
	// Configuration Options
	// --------------------------------------------------------- //
	var homeSliderAutoAnimated = Boolean(ThemeOptions.default_slider_auto_rotation);
	var homeSliderAutoAnimatedDelay = parseInt(ThemeOptions.default_slider_rotation_duration, 10);
	var homeSliderAnimation = ThemeOptions.default_slider_transition;
	var homeSliderAnimationSpeed = parseInt(ThemeOptions.default_slider_transition_speed, 10);
	var homeSliderCaptionAnimated = Boolean(ThemeOptions.default_slider_caption_animation);

	var imageSliderAnimationSpeed = 700;
	// ---------------------------------------------- //



	// ---------------------------------------------- //
	// Global Read-Only Variables (DO NOT CHANGE!)
	// ---------------------------------------------- //
	var isSearchDisplayed = false;

	var isHomeSliderLoaded = false;
	var isHomeSliderFirstTimeHovered = false;

	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = ua.indexOf("android") > -1;
	var androidversion = parseFloat(ua.slice(ua.indexOf('android') + 8));
	
	var contentWidth = parseInt($('.columns-content-width').css('width').replace('px', ''), 10);
	// ---------------------------------------------- //



	// ---------------------------------------------- //
	// Core Scripts
	// ---------------------------------------------- //

	// Initialize custom functions
	renderGoogleMaps('all');
	initMobileMenu();

	// Initialize Foundation framework
	$(document).foundation();
	
	// To move the meta info (in single page) inside the VC row-column element (if VC is in use here)
	if(contentWidth > 480) {
		$('#inner-blog-single-item .blog-meta').prependTo('#single-content-wrapper .vc_row:first .vc_column-inner');
		
		// Hide loading text
		$('#inner-blog-single-item .loading-text').css('display', 'none');
		
		// To prevent the content "jumping" in single post page by initially hide the elements then show them once the above prepending is done.
		$('#single-content-wrapper, #inner-blog-single-item .blog-meta, #inner-blog-single-item .blog-title').stop().animate({ opacity: 1 });
	}

	// Force displaying tabs element after JS has been loaded
	$('#content-container .section-container').addClass('display-block');

	// Add CSS class to submit button of comment form
	$('input#submit, input[type="submit"], input[type="button"]').addClass('button');

	// To remove some empty tags
	$('p:empty').remove(); // This is mostly added by WP automatically

	// To unwrap "p" tag out of "x" button of the message box
	if ($('.box .close').length > 0) {
		if ($('.box .close').parent().prop('tagName').toLowerCase() === 'p') {
			$('.box .close').unwrap();
		}
	}

	// To remove margin-bottom out of the last "p" element inside the message box
	$('.box').find('p:last-child').addClass('no-margin-bottom');

	// For later use in FancyBox call
	var waitForFinalEvent = (function () {
	  var timers = {};
	  return function (callback, ms, uniqueId) {
		if (!uniqueId) {
		  uniqueId = "Don't call this twice without a uniqueId";
		}
		if (timers[uniqueId]) {
		  clearTimeout (timers[uniqueId]);
		}
		timers[uniqueId] = setTimeout(callback, ms);
	  };
	})();
	
	// Social Icons
	if ( $( '#header-social-icons' ).length > 0 ) {
		$( 'body' ).addClass( 'header-social-icons' );
	}



	/***** Home Slider *****/
	if (jQuery().flexslider) {

		if ($('#home-slider-container').length > 0) {
			
			if (!homeSliderCaptionAnimated) {
				$('.slider-caption .caption-title, .slider-caption .caption-body').css('opacity', 1);
			}
			
			$('#home-slider-container').imagesLoaded(function() {
			
				$('#home-slider-container').flexslider({
					animation : homeSliderAnimation,
					directionNav : false,
					contolNav : false,
					pauseOnAction : true,
					pauseOnHover : true,
					slideshow : homeSliderAutoAnimated,
					slideshowSpeed : homeSliderAutoAnimatedDelay,
					animationSpeed : homeSliderAnimationSpeed,
					selector : '.home-slider-slides > li',
					initDelay : 2000,
					smoothHeight : true,
					start : function(slider) {
	
						var initFadingSpeed = 500;
						var initDelay = 0;
						// "slide" effect has some different transition to re-define
						if (homeSliderAnimation == 'slide') {
							initFadingSpeed = 1;
							initDelay = 800;
						}
	
						$('#home-slider-container .home-slider-slides, #home-slider-container .flex-viewport').stop().animate({// "fade" and "slide" effect container
							opacity : 1
						}, initFadingSpeed, function() {
	
							if (homeSliderCaptionAnimated) {
								$(slider.slides[slider.currentSlide]).find('.slider-caption .caption-title').delay(initDelay).stop().animate({
									opacity : 1
								}, 500, function() {
									$(slider.slides[slider.currentSlide]).find('.slider-caption .caption-body').stop().animate({
										opacity : 1
									}, 800);
	
								});
							}
						});
	
						isHomeSliderLoaded = true;
	
						// Hide loading gif
						$(slider).closest('#home-slider-container').css({
							background : 'none',
							// reset init height fix for Safari (also working on other browsers). this will also set the inline height based on the first slide's image
							height : $(slider).closest('#home-slider-container').find('.home-slider-slides li.flex-active-slide img').height() + 'px',
						}).addClass('auto-height');
	
					},
					before : function() {
						if (homeSliderCaptionAnimated) {
							$('.slider-caption .caption-title, .slider-caption .caption-body').stop().animate({
								opacity : 0
							}, 1);
						}
					},
					after : function(slider) {
	
						$(slider).closest('#home-slider-container').css('height', 'inherit');
	
						if (homeSliderCaptionAnimated) {
	
							$(slider.slides[slider.currentSlide]).find('.slider-caption .caption-title').stop().animate({
								opacity : 1
							}, 500, function() {
								$(slider.slides[slider.currentSlide]).find('.slider-caption .caption-body').stop().animate({
									opacity : 1
								}, 800);
	
							});
						}
					}
				});
				
			});
				
			$('#home-slider-container .slider-prev').on('click', function() {
				$(this).closest('.slider-set').flexslider('prev');
				return false;
			});
			$('#home-slider-container .slider-next').on('click', function() {
				$(this).closest('.slider-set').flexslider('next');
				return false;
			});

			// Display slider controller on hovered
			if ( $('.home-slider-item').length > 1 ) {
					
				$('#home-slider-container').hover(function() {
					$(this).find('.slider-controller').css('display', 'block').stop().animate({
						opacity : 1
					}, 100);
					
				}, function() {
					$(this).find('.slider-controller').stop().animate({
						opacity : 0
					}, 300);
				});

			}
			
		}

	}



	/***** Menu *****/
	// Only for columned menu layout
	if ( ! $('#menu-wrapper').hasClass('horizontal-menu')) {
		
		// Firstly, replicate the displaying menu from the default menu UL
		// then divide the menu items into columns
		var menuItems = $('#rendered-menu-wrapper > ul.main-menu > li').clone();
		var menuItemCount = $(menuItems).length;
		var itemCounter = 1;
		var menuColumnDiv = $('<div class="menu-column" />');
		var menuColumnUl = $('<ul class="sf-menu sf-vertical main-menu" />');
				
		$(menuItems).each(function(index) {
			
			// Add cloned list items into the created UL
			$(menuColumnUl).append($(this));
			//console.debug($(this).children());
			
			// 3 items per column
			if (itemCounter == 3 || index == menuItemCount - 1) {
				
				// Add menu UL into the column wrapper and then into root menu wrapper
				$('#rendered-menu-wrapper').append($(menuColumnDiv).append($(menuColumnUl)));
				
				// Prepare another set of column wrapper
				menuColumnDiv = $('<div class="menu-column" />');
				menuColumnUl = $('<ul class="sf-menu sf-vertical main-menu" />');
				
				// Reset the counter
				itemCounter = 1;
				
			} else {
				itemCounter += 1;
			}
			
		});
		
		// Fade-in the complete relicated menu
		$('#rendered-menu-wrapper').stop().animate({
			opacity : 1,
		});
		
		// Remove the default UL set
		$('#rendered-menu-wrapper > ul.main-menu').hide();
		
	}
	
	// Load menu engine
	if (jQuery().superfish) {
		$('.sf-menu').superfish({
			animation : {
				height : 'show'	// slide-down effect without fade-in
			},
			speed : 'normal',
			speedOut : 'normal',
			delay : 400	// 0.4 second delay on mouseout
		});

	}

	// Set marker for active menu
	var menuParent = $('.main-menu > .current-menu-item, .main-menu > .current-menu-parent');
	menuParent.append('<span class="menu-marker"></span>');
	$('.menu-marker').stop().animate({
		opacity : 1
	});

	// Menu on hovered
	$('.main-menu > li').mouseover(function() {
		$('.main-menu > li > a, .menu-marker').not($(this).children('a')).stop().animate({
			opacity : 0.2
		});
	}).mouseout(function() {
		$('.main-menu > li > a, .menu-marker').not($(this).children('a')).stop().animate({
			opacity : 1
		}, 600);
	});

	// Header Search Button
	$('#header-search-button').click(function() {
		if (!isSearchDisplayed) {

			// Hide menu
			$('.menu-column').stop().animate({
				opacity : 0
			}, 300, function() {
				$(this).css('display', 'none');
			});
			$('#menu-wrapper.horizontal-menu .main-menu').stop().animate({
				opacity : 0
			}, 300);

			//$('#mobile-menu > ul').css('visibility', 'hidden');
			$('#mobile-menu > ul').stop().animate({
				opacity : 0
			});

			// Display search input
			$('#header-search-input-wrapper').css('display', 'block').stop().animate({
				opacity : 1
			}, 300);

			$('#header-search-button').addClass('cancel').find('i').removeClass().addClass('fa fa-remove');
			$('#header-search-input').focus();

			isSearchDisplayed = true;
		} else {
			// Show menu
			$('.menu-column').css('display', '').stop().animate({
				opacity : 1
			}, 300);
			$('#menu-wrapper.horizontal-menu .main-menu').stop().animate({
				opacity : 1
			}, 300);
			
			//$('#mobile-menu > ul').css('visibility', 'visible');
			$('#mobile-menu > ul').stop().animate({
				opacity : 1
			}, 400);

			// Hide search input
			$('#header-search-input-wrapper').stop().animate({
				opacity : 0
			}, 200, function() {
				$(this).css('display', 'none');
			});

			$('#header-search-button').removeClass('cancel').find('i').removeClass().addClass('fa fa-search');

			isSearchDisplayed = false;
		}

	});

	

	/***** Image Slider *****/
	if (jQuery().flexslider) {

		var imageSlider = $('.image-slider-wrapper');
		imageSlider.each(function() {

			var autoRotate = $(this).attr('data-auto-rotation'), 
				imageSliderAutoAnimated = true, 
				imageSliderAutoAnimatedDelay = 10000;
				
			if (autoRotate !== '0') {
				imageSliderAutoAnimatedDelay = parseInt(autoRotate, 10) * 1000;
				// Convert to milliseconds
			} else {
				imageSliderAutoAnimated = false;
			}
			
			var imageSliderAnimation = $(this).attr('data-effect');
			
			var $currentSlider = $(this);
			
			$currentSlider.imagesLoaded(function() {

				$currentSlider.flexslider({
					animation : imageSliderAnimation,
					directionNav : false,
					contolNav : false,
					pauseOnAction : true,
					pauseOnHover : true,
					slideshow : imageSliderAutoAnimated,
					slideshowSpeed : imageSliderAutoAnimatedDelay,
					animationSpeed : imageSliderAnimationSpeed,
					selector : '.image-slider > li',
					initDelay : 2000,
					smoothHeight : true,
					start : function(slider) {

						var initFadingSpeed = 800;
						var initDelay = 0;
						// "slide" effect has some different transition to re-define
						if (imageSliderAnimation == 'slide') {
							initFadingSpeed = 1;
							initDelay = 800;
						}
//alert($currentSlider.html());
						$currentSlider.find('.image-slider, .flex-viewport').css('visibility', 'visible').stop().animate({
							opacity : 1,
						}, initFadingSpeed);
						
						// Whether the border is enabled or not
						var borderEnabled = $currentSlider.find('.image-slider li.flex-active-slide img').hasClass('border');
						var extraInitHeight = 16; // border top + bottom heights
						if( ! borderEnabled) { // if not, then there is no extra initial height
							extraInitHeight = 0;
						}

						// Hide loading gif
						$currentSlider.css({
							background : 'none',
							// reset init height fix for Safari (also working on other browsers). this will also set the inline height based on the first slide's image
							height : $currentSlider.find('.image-slider li.flex-active-slide img').height() + extraInitHeight + 'px',
						}).addClass('auto-height');

						$currentSlider.closest('.image-slider-root-container').attr('data-loaded', 'true');
						
					},
					before : function() {
					},
					after : function(slider) {
						// set a new height based on the next slide
						$currentSlider.css('height', 'inherit');
					},
				});
				// END: flexslider

			});
			//END: imageLoaded

		});
		// END: each

		$('.image-slider-root-container .slider-prev').on('click', function() {
			$(this).closest('.image-slider-root-container').find('.slider-set').flexslider('prev');
			return false;
		});
		$('.image-slider-root-container .slider-next').on('click', function() {
			$(this).closest('.image-slider-root-container').find('.slider-set').flexslider('next');
			return false;
		});

		// Display slider controller on hovered
		$('.image-slider, .slider-controller').hover(function() {
			var root = $(this).closest('.image-slider-root-container');
			if ($(root).find('.image-slider-item:not(.clone)').length > 1) {
				if ($(root).attr('data-loaded') == 'true') {// works only when the slider is loaded
					$(root).attr('data-first-hover', 'true');
					// this is used to prevent the "mousemove" event below continuously firing the handler
					$(root).find('.slider-controller').css('display', 'block').stop().animate({
						opacity : 1
					});
				}
			}
		}, function() {
			var root = $(this).closest('.image-slider-root-container');
			$(root).find('.slider-controller').stop().animate({
				opacity : 0
			});
		});
		// If the mouse cursor is moving on the slider when it is just loaded, display the controller
		$('.image-slider, .slider-controller').mousemove(function() {
			var root = $(this).closest('.image-slider-root-container');
			if ($(root).find('.image-slider-item:not(.clone)').length > 1) {
				if ($(root).attr('data-first-hover') != 'true' && $(root).attr('data-loaded') == 'true') {
					$(root).find('.slider-controller').css('display', 'block').stop().animate({
						opacity : 1
					});
				}
			}
		});
		
		// Some sliders that are in "large-6" column (only left column) might display some 1px glitch.
		// To fix that, using the JS code below to reduce the width by 1px to hide it.
		var slidersToBeFixed = $('.row .large-6.columns:first-child .image-slider-root-container');
		$(slidersToBeFixed).each(function() {
			$(this).css('width', $(this).width() - 1 );
		});

	}
	
	
	
	/***** Portfolio Single Page *****/
	movePortfolioTextMeta();
	function movePortfolioTextMeta() {
		
		if ( $('.port-content').hasClass('landscape') ) {
			
			if ( Modernizr.mq('(max-width: 768px)') ) {
				$('.port-text').before($('.port-meta'));
			} else {
				$('.port-meta').before($('.port-text'));
			}
			
		}
		
	}
	



	


	// ---------------------------------------------- //
	// Elements / Misc.
	// ---------------------------------------------- //

	/***** Google Maps *****/
	function renderGoogleMaps( mapSelector ) {

		if ( typeof google !== 'undefined' && typeof google.maps !== 'undefined' && typeof google.maps.MapTypeId !== 'undefined') {
			
			var elements = $('.google-map');
			if ( mapSelector != 'all' ) {
				elements = mapSelector;
			}

			elements.each(function() {

				var rawlatlng = $(this).attr('data-latlng').split(',');
				var lat = jQuery.trim(rawlatlng[0]);
				var lng = jQuery.trim(rawlatlng[1]);
				var address = $(this).attr('data-address');
				var displayType = $(this).attr('data-display-type');
				var zoomLevel = parseInt($(this).attr('data-zoom-level'), 10);
				$(this).css('height', $(this).attr('data-height'));

				switch(displayType.toUpperCase()) {
					case 'ROADMAP' :
						displayType = google.maps.MapTypeId.ROADMAP;
						break;
					case 'SATELLITE' :
						displayType = google.maps.MapTypeId.SATELLITE;
						break;
					case 'HYBRID' :
						displayType = google.maps.MapTypeId.HYBRID;
						break;
					case 'TERRAIN' :
						displayType = google.maps.MapTypeId.TERRAIN;
						break;
					default :
						displayType = google.maps.MapTypeId.ROADMAP;
						break;
				}

				var geocoder = new google.maps.Geocoder();
				var latlng = new google.maps.LatLng(lat, lng);
				var myOptions = {
					scrollwheel : false,
					zoom : zoomLevel,
					center : latlng,
					mapTypeId : displayType
				};

				var map = new google.maps.Map($(this)[0], myOptions);

				geocoder.geocode({
					'address' : address,
					'latLng' : latlng,
				}, function(results, status) {
					if (status === google.maps.GeocoderStatus.OK) {
						var marker;
						if (jQuery.trim(address).length > 0) {
							marker = new google.maps.Marker({
								map : map,
								position : results[0].geometry.location
							});

							map.setCenter(results[0].geometry.location);

						} else {
							marker = new google.maps.Marker({
								map : map,
								position : latlng
							});

							marker.setPosition(latlng);
							map.setCenter(latlng);

						}

					} else {
						window.alert("Geocode was not successful for the following reason: " + status);
					}
				});

			});
		}

	}


	
	/***** Fancybox *****/
	var enableLightbox = Boolean(ThemeOptions.enable_lightbox_wp_gallery);
	
	// Add FancyBox feature to WP gallery and image shortcode
	if (enableLightbox) {
		
		registerFancyBoxToWPGallery();
		registerFancyBoxToWPImage();
		callFancyBoxScript();
		
	}
	
	function registerFancyBoxToWPGallery() {
		// WP Gallery shortcode
		var $wpGallery = $('.gallery');

		$wpGallery.each(function() {
			var mainId = $(this).attr('id');

			var items = $(this).find('.gallery-item').find('a');

			items.each(function() {

				var href = $(this).attr('href');

				if (href.toLowerCase().indexOf('.jpg') >= 0 || href.toLowerCase().indexOf('.jpeg') >= 0 || href.toLowerCase().indexOf('.png') >= 0 || href.toLowerCase().indexOf('.gif') >= 0) {

					$(this).addClass('image-box');
					$(this).attr('rel', mainId);

				}

			});

		});
	}
	
	function registerFancyBoxToWPImage() {
		
		// Run through WP images on the page
		$('img[class*="wp-image-"]').each( function() {
			
			// If the image has an anchor tag
			var $parentAnchor = $(this).closest('a');
			
			if ( $parentAnchor.length > 0 ) {
				
				var href = $parentAnchor.attr('href');
				
				// Check the target file extension, if it is one of the image extension then add Fancybox class
				if (href.toLowerCase().indexOf('.jpg') >= 0 || href.toLowerCase().indexOf('.jpeg') >= 0 || href.toLowerCase().indexOf('.png') >= 0 || href.toLowerCase().indexOf('.gif') >= 0) {

					$parentAnchor.addClass('image-box');

				}
				
			}
			
		});
		
	}

	function callFancyBoxScript() {

		if (jQuery().fancybox) {
			
			// Replace VC's prettyphoto class with theme's and make sure to only apply to img with anchor tags, not heading with anchor tags
			$('#content-container a').each(function() {
				
				if ( $(this).hasClass('prettyphoto') ) {
					
					// Change from VC prettyphoto to the theme's fancybox
					$(this).removeClass('prettyphoto').addClass('image-box');
					
					// In case the VC "rel" attr exists
					if ( typeof $(this).attr('rel') !== 'undefined' && $(this).attr('rel') !== false ) {
						
						$(this).attr( 'rel', $(this).attr('rel').replace('prettyPhoto[', '').replace(']', '') );
						
					} else if ( typeof $(this).attr('data-rel') !== 'undefined' && $(this).attr('data-rel') !== false ) {
						
						// Since VC4.11.1, they has changed from "rel" to "data-rel" so we need to use it like this instead:
						$(this).attr( 'rel', $(this).attr('data-rel').replace('prettyPhoto[', '').replace(']', '') );
					
					} else {
						$(this).attr( 'rel', 'missing-rel-attr' );
					}
					
				}
				
			});
			
			if (isAndroid && androidversion <= 4.0) {
				// Fancybox's thumbnail helper is not working on older Android, so disable it.
				$('.image-box, .vc_gitem-zone a, .uxb-port-image-box').not('.clone .image-box').fancybox();
			} else {
				
				$('.image-box, .vc_gitem-zone a, .uxb-port-image-box').not('.clone .image-box').fancybox({
					helpers : {
						thumbs : {
							width : 50,
							height : 50
						},
						overlay: {
							locked: false, // to prevent page jumping to the top when clicking on the object
					    }
					},
					beforeLoad: function() {
			            this.title = $(this.element).find('img').attr('alt');
			        }
				});
				
			}
			
		}
		
	}
	
	// If there is any VC's media grid element, initialize an interval check before calling FancyBox script
	// because some related HTML elements are not populated in the first load
	if ( $('.vc_grid-container-wrapper').length > 0 ) {
		
		$('.vc_grid-container-wrapper').each(function() {
			var $grid = $(this);
			if ( $grid.find('.vc_grid-container').attr('class').indexOf('media_grid') != -1  ) {
				
				var checkExist = setInterval(function() {
				   if ( $grid.find('.vc_grid-loading').length == 0 ) {
					  
					  // Call Fancybox after the media grid is finished loading
					  callFancyBoxScript();
					  //console.log('Finish loading');
					  clearInterval(checkExist);
					  
				   } else {
					   //console.log('Still loading');
				   }
				   
				   
				}, 500); // check every 500ms
				
				/*waitForFinalEvent(function() {
					
					if ( $(this).find('.vc_grid-loading').length == 0 ) {
						callFancyBoxScript();
					}
					//console.log('test');

				}, 1000, 'vc_media_grid_fancybox');
				*/
			}
			
		});
		

	}



	/***** Tabs *****/
	if ($('html').hasClass('lt-ie9')) {
		$('.auto').addClass('tabs').removeClass('auto').attr('data-section', 'tabs');
	}
	
	// For Tabs and Accordions when clicked to re-render the contained maps
	$('.wpb_tabs_nav li a, .vc_tta-tab a, .vc_tta-panel .vc_tta-panel-heading a, .wpb_accordion_header').on('click', function() {
		
		// For new VC Tab and new Accordion
		if ( $(this).closest('.vc_tta-container').length > 0 ) {
			
			var panelBody = $( $(this).attr('href') ).find('.vc_tta-panel-body');
			$(panelBody).css('display', 'none').css('display', 'block');
			renderGoogleMaps( $(panelBody).find('.google-map') );
		
		} else if ( $(this).closest('.wpb_accordion_section').length > 0 ) {
		
			// For old Accordion
			var accContent = $(this).closest('.wpb_accordion_section').find('.wpb_accordion_content');
			$(accContent).css('display', 'none').css('display', 'block');
			renderGoogleMaps( $(accContent).find('.google-map') );
			
		} else {
				
			// For old VC Tab
			// Need to hide/show to make contained google maps working
			var tabContent = $( $(this).attr('href') );
			$(tabContent).css('display', 'none').css('display', 'block');
			renderGoogleMaps( $(tabContent).find('.google-map') );
			
		}
		
	});


	
	
	
	/***** ScrollUp Button *****/
	if (jQuery().scrollUp) {
		$.scrollUp({
			scrollSpeed: 700,
			easingType: 'easeOutQuint',
			scrollText: '',
		});
	}

	
	


	/***** Mobile Menu *****/
	function initMobileMenu() {
		//var defaultMenuList = $('#root-menu');
		var mobileMenuList = $('<ul />').appendTo($('#mobile-menu .top-bar-section'));

		var clonedList = $('#menu-wrapper .main-menu > li').clone();
		clonedList = getGeneratedSubmenu(clonedList);
		clonedList.appendTo(mobileMenuList);

	}

	// Recursive function for generating submenus
	function getGeneratedSubmenu(list) {
		//console.debug($('#menu-wrapper .main-menu > li'));
		$(list).each(function() {
			//$(this).append('<li class="divider"></li>');

			if ($(this).find('ul').length > 0) {
				var submenu = $(this).find('ul');

				$(this).addClass('has-dropdown');
				submenu.addClass('dropdown');

				getGeneratedSubmenu(submenu.find('li'));
			}
		});

		return list;
	}
	
	
	
	
	/***** WooCommerce *****/
	//$('.inline.show_review_form.button').addClass('small').prepend('<i class="icon-plus"></i>');
	$('.single_add_to_cart_button.button, .add_to_cart_button.button').prepend('<i class="icon-shopping-cart"></i>');
	$('.button.product_type_variable').prepend('<i class="icon-wrench"></i>');
	$('.button.product_type_simple').not('.add_to_cart_button').prepend('<i class="icon-file-alt"></i>');

	// To select the review tab when changing the current review page
	if(document.location.hash!='') {
		
	    // Get the index from URL hash
	    var tabSelect = document.location.hash.substr(1,document.location.hash.length);
	    
	    // Jump to the tabs location
	    if(tabSelect == 'comment-1') {
	    	
	    	var queryStrings = getUrlVars();
	    	
	    	// If the page is reloaded after adding item to cart, don't do scrolling to review area
	    	if(typeof queryStrings['added-to-cart'] === 'undefined') {
		    	
		    	//document.getElementsByClassName('woocommerce-tabs')[0].scrollIntoView();
		    	if(jQuery().scrollintoview) {
		    		$('.woocommerce-tabs').scrollintoview();
		    	}
		    	
	    	}
	    }
	    
	}
	
	$('.woocommerce.widget_product_search #searchform').css('display', 'block');
	
	
	/***** Utils Functions *****/
	/*** Getting query string ***/
	function getUrlVars() {
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++) {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}
	
	
	/***** Responsive Related *****/
	$(window).on('resize', function() {
		
		movePortfolioTextMeta();
		
	});
	
});
