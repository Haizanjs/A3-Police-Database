jQuery(document).ready(function($) {

	'use strict';

/*==========================================================*/
/* Collapsible sidebar
/*==========================================================*/

	$('#sidebar-button, #overlay').click(function() {
		$('.portfolio-full').removeClass('portfolio-open');
		$('#top').removeClass('portfolio-open');
		$('#sidebar-button').toggleClass('open');
		$('body').toggleClass('sidebar-open');
		return false;
	});

/*==========================================================*/
/* Main menu
/*==========================================================*/

	$('#mainmenu ul > li:has(ul)').each(function() {
		$(this).addClass('expandable');
	});

	$('#mainmenu ul > li:has(ul) > a').click(function() {
		$(this).parent('li').toggleClass('expanded');
		$(this).parent('li').children('ul').slideToggle();
		return false;
	});

	/* Fullscreen slider */

	var fSwiper = new Swiper('#fullscreen-slider',{
		onSwiperCreated: function() {
			// Slide has video
			if ($('#fullscreen-slider .swiper-slide-active').has('video').length) {
				$('#fullscreen-slider .swiper-slide-active video').get(0).play();
			}
		},
		onSlideChangeStart: function() {
			// Stop videos in slider
			$('#fullscreen-slider .swiper-slide').each(function() {
				if ($(this).has('video').length) {
					$(this).children('video').get(0).pause();
				}
			});
			// Hide arrow on first and last slide
			if (fSwiper.activeIndex == 0) {
				$('#nav-arrows .nav-left').addClass('hidden');
			} else {
				$('#nav-arrows .nav-left').removeClass('hidden');
			}
			if (fSwiper.activeIndex == (fSwiper.slides.length - 1)) {
				$('#nav-arrows .nav-right').addClass('hidden');
			} else {
				$('#nav-arrows .nav-right').removeClass('hidden');
			}
		}
	});
	// Bind external navigation arrows for fullscreen slider
	$('#nav-arrows .nav-left').on('click', function(e){
		e.preventDefault();
		fSwiper.swipePrev();
	});
	$('#nav-arrows .nav-right').on('click', function(e){
		e.preventDefault();
		fSwiper.swipeNext();
	});

/*==========================================================*/
/* Masonry blog
/*==========================================================*/

	// 3 columns
	$('.masonry-3').masonry({
		itemSelector: 'article',
		columnWidth: '.col-4'
	});

	// 4 columns
	$('.masonry-4').masonry({
		itemSelector: 'article',
		columnWidth: '.col-3'
	});

/*==========================================================*/
/* Isoptope
/*==========================================================*/

	$('.isotope').isotope({
		resizable: 'false',
		itemSelector: '.isotope-item',
		masonry: {
			columnWidth: colW()
		}
	});

	/* Smart resize */

	function colW() {
		var colN;
		if ($('.isotope').hasClass('isotope-2')) {
			colN = 2;
		} else if ($('.isotope').hasClass('isotope-3')) {
			colN = 3;
		} else {
			colN = 4;
		}
		var colW = Math.floor($('.isotope').width() / colN);
		$('.isotope').find('.isotope-item').each(function() {
			$(this).css({
				width: colW
			});
		});
		return colW;
	}

	$(window).smartresize(function(){
		$('.isotope').isotope({
			masonry: {
				columnWidth: colW()
			}
		});
	});

	/* Filter */

	$('.filter-dropdown ul li').click(function(){
		var selector = $(this).attr('data-filter');
		$('.isotope').isotope({
			filter: selector
		});
	});

	/* Dropdown list */

	$('.filter-dropdown').click(function(){
		$(this).toggleClass('open');
	});

	$('.filter-dropdown ul li').click(function(){
		$(this).parent('ul').prev('.selected').children('span.val').text($(this).text());
	});
/*==========================================================*/
/* AJAX Contact form
/*==========================================================*/

	$('#contact-form').submit(function() {
		$.post('send.php', $(this).serialize(), function(data){
			$('#contact-form').html('<p>' + data + '</p>');
		});
		return false;
	});

});(jQuery);