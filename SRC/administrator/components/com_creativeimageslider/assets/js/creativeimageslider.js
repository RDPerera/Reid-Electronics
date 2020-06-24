(function($) {
$(window).load(function() {

	//claculate proper width
	function cis_calculate_width() {
		$('.cis_images_holder').each(function() {
			var $wrapper = $(this);
			var total_w = 0;
			$wrapper.find('.cis_row_item').each(function() {
				$(this).find('img').css('width','auto');
				var w = parseInt($(this).find('img').width());
				$(this).find('img').width(w);
				var m_r = isNaN(parseFloat($(this).css('margin-right'))) ? 0 : parseFloat($(this).css('margin-right'));
				var m_l = isNaN(parseFloat($(this).css('margin-left'))) ? 0 : parseFloat($(this).css('margin-left'));
				total_w += w + m_r*1 + m_l*1;
			});
			total_w = total_w + 1*1;
			$wrapper.width(total_w);
		});
	};
	
	setTimeout(function() {
		cis_calculate_width();
	},400);	

	//resize
	$(window).resize(function() {
	  cis_calculate_width();
	});
	
	$(".cis_row_item img").each(function() {
		var $cis_overlay = $(this).next('.cis_row_item_overlay');
		if($cis_overlay.attr('cis_animation') == 'enabled')
			return;
		$cis_overlay.css({'visibility' : 'hidden','display' : 'block'});
		var h = $cis_overlay.height();
		$cis_overlay.css({'visibility' : 'visible','display' : 'block','height' : '0'}).attr('h',h);
		$cis_overlay.attr('cis_animation','enabled');
	});
	
	$(".cis_row_item img").each(function() {
		if($(this).attr('cis_loaded') == 'loaded')
			return;
		cis_make_pr($(this));
	});
	
	function cis_make_pr($el) {
		if($el.attr('cis_loaded') == 'loaded')
			return;
		var item_width = $el.width();
		$el.parents('.cis_row_item').find('.cis_row_item_loader').animate({
			width: item_width
		},400,'swing',function() {
			$el.parents('.cis_row_item').find('.cis_row_item_loader').fadeOut(200,function() {
				$el.parents('.cis_row_item_inner').hide().removeClass('cis_row_hidden_element').fadeIn(200);
			});
		});
	};
	
});

$(document).ready(function() {
	//creative popup///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/*
	//make items ordering
	*/
	function cis_make_creative_items_orders() {
		$('.cis_main_wrapper').each(function(){
			var curr_order = 0;
			$(this).find('.cis_row_item').each(function() {
				$(this).attr("cis_item_order",curr_order)
				curr_order ++;
			})
		})
	};
	cis_make_creative_items_orders();


	//slider correction////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$('.cis_row_item').mouseenter(function() {
		cis_make_slider_item_correction($(this));
	});

	function cis_make_slider_item_correction($elem) {
		var $loader = $elem.find('.cis_row_item_loader'); 
		var slider_id = $loader.parents('.cis_main_wrapper').attr("cis_slider_id");
		var item_id = $loader.parents('.cis_row_item').attr("item_id");

		//check if slider in scroll progress, then return
		if($loader.parents('.cis_main_wrapper').find('.cis_images_holder').hasClass('cis_scrolling'))
			return;

		var $cis_popup = $('.cis_popup_wrapper');

		var loader_width = parseInt($loader.css('width'));
		var items_m_r = parseInt($loader.parents('.cis_row_item').css('margin-right'));

		//get slider_offset
		var image_index = $loader.parents('.cis_row_item').attr("cis_item_order");
		var total_items_width = 0;
		$loader.parents('.cis_main_wrapper').find('.cis_row_item').each(function(i) {
			var w = parseInt($(this).width());
			var m_r = parseInt($(this).css('margin-right'));
			total_items_width = total_items_width + 1*w + 1*m_r;
			if(i == image_index)
				return false;
		});

		var current_left_offset = Math.abs(parseInt($loader.parents('.cis_main_wrapper').find('.cis_images_holder').css('margin-left')));
		var wrapper_width = parseInt($loader.parents('.cis_main_wrapper').width());

		var offset1 = total_items_width - current_left_offset;
		var direction = 0;
		var item_offset_to_move = 0;
		if(offset1 >= wrapper_width) {
			var item_offset = total_items_width - current_left_offset - wrapper_width - items_m_r;
			var item_offset_to_move = item_offset < 0 ? 0 : item_offset;
		}
		else {
			if(offset1 < loader_width) {
				var item_offset_to_move = loader_width - offset1 + 1*items_m_r;
				direction = 1;
			}
		};

		var popup_loader_animate_timeout = 400;
		if(item_offset_to_move > 0) {
			// popup_loader_animate_timeout = Math.abs(item_offset_to_move) * 4;
			if(direction == 1) {
				$loader.parents('.cis_main_wrapper').find('.cis_images_holder').stop().animate({
					'margin-left': "+=" + item_offset_to_move
				},popup_loader_animate_timeout,'swing');
			}
			else {
				$loader.parents('.cis_main_wrapper').find('.cis_images_holder').stop().animate({
					'margin-left': "-=" + item_offset_to_move
				},popup_loader_animate_timeout,'swing');
			};
		};
	};

	var cis_interval_time = 250;
	
	//arrows
	function cis_prepare_arrows() {
		$(".cis_main_wrapper").each(function() {
			var $wrapper = $(this);
			var $left_arrow = $wrapper.find('.cis_button_left');
			var $right_arrow = $wrapper.find('.cis_button_right');
			
			//get arrows data
			var arr_data = $wrapper.find('.cis_arrow_data').html();
			var arr_data_array = arr_data.split(',');
			var arrow_width = arr_data_array[0];
			var arrow_corner_offset = arr_data_array[1];
			var arrow_middle_offset = arr_data_array[2];
			var arrow_opacity = arr_data_array[3] / 100;
			var show_arrows = arr_data_array[4];
			
			//set data
			$left_arrow.attr("op",arrow_opacity);
			$left_arrow.attr("corner_offset",arrow_corner_offset);
			$right_arrow.attr("op",arrow_opacity);
			$right_arrow.attr("corner_offset",arrow_corner_offset);
			
			//set styles
			$left_arrow.css('width',arrow_width);
			$right_arrow.css('width',arrow_width);
			
			var arrow_height = parseInt ($left_arrow.height());
			var wrapper_height = parseFloat ($wrapper.height());
			var p_t = isNaN(parseFloat($wrapper.css('padding-top'))) ? 0 : parseFloat($wrapper.css('padding-top'));
			var p_b = isNaN(parseFloat($wrapper.css('padding-bottom'))) ? 0 : parseFloat($wrapper.css('padding-bottom'));
			var arrow_top_position = ((wrapper_height + 1 * p_t + 1 * p_b - arrow_height) / 2 ) + 1 * arrow_middle_offset;
			
			$left_arrow.css({
				'top': arrow_top_position,
				'left': '-64px',
				'opacity': arrow_opacity
			});
			$right_arrow.css({
				'top': arrow_top_position,
				'right': '-64px',
				'opacity': arrow_opacity
			});
			
			if(show_arrows == 0) {//never show arrows
				$left_arrow.remove();
				$right_arrow.remove();
			}
			else if(show_arrows == 1) {//show on hover
				$wrapper.hover(function() {
					cis_show_arrows($wrapper);
				}, function() {
					cis_hide_arrows($wrapper);
				})
			}
			else {
				cis_show_arrows($wrapper);
			}
		});
	};
	setTimeout(function() {
		cis_prepare_arrows();
	},1200);
	
	var cis_arrows_timeout1 = '';
	var cis_arrows_timeout2 = '';
	function cis_show_arrows($wrapper) {
		//clear timeouts
		clearTimeout(cis_arrows_timeout1);
		clearTimeout(cis_arrows_timeout2);
		
		var $left_arrow = $wrapper.find('.cis_button_left');
		var $right_arrow = $wrapper.find('.cis_button_right');
		
		var corner_offset = $left_arrow.attr("corner_offset");
		
		var animation_time = 400;
		var start_offset = -64;
		var effect = 'easeOutBack';
		
		cis_arrows_timeout1 = setTimeout(function() {
			$left_arrow.stop(true,false).animate({
				'left': corner_offset
			},animation_time,effect);
			
			$right_arrow.stop(true,false).animate({
				'right': corner_offset
			},animation_time,effect);
		},100);
		
	};
	function cis_hide_arrows($wrapper) {
		//clear timeouts
		clearTimeout(cis_arrows_timeout1);
		clearTimeout(cis_arrows_timeout2);
		
		var $left_arrow = $wrapper.find('.cis_button_left');
		var $right_arrow = $wrapper.find('.cis_button_right');
		
		var animation_time = 300;
		var start_offset = -64;
		var effect = 'easeInBack';
		
		cis_arrows_timeout2 = setTimeout(function() {
			$left_arrow.stop(true,false).animate({
				'left': start_offset
			},animation_time,effect);
			
			$right_arrow.stop(true,false).animate({
				'right': start_offset
			},animation_time,effect);
		},200)
	};
	
//mousewheel**************************************************************
	// $('.cis_images_row').mousewheel(function(objEvent, intDelta) {
	// 	if($(this).hasClass('cis_scrolling_vertical'))
	// 		return;
	// 	if(intDelta > 0)
	// 		cis_move_images_holder_left($(this).find('.cis_images_holder'));
	// 	else 
	// 		cis_move_images_holder_right($(this).find('.cis_images_holder'));
	// });
	
	// setTimeout(function() {
	// 	//cis_move_images_holder_right($('.cis_images_holder'));
	// },250);
	
	//function to move left

	var effect_type = 'swing';
	var cis_clear_timeout = '';
	var cis_switch_move_direction = false;
	
	//buttons
	$('.cis_button_left').hover(function() {
		$(this).animate({
			'opacity' : 1
		},300);
	},function() {
		var opacity_inactive = $(this).attr("op");
		$(this).animate({
			'opacity' : opacity_inactive
		},300);
	});
	$('.cis_button_right').hover(function() {
		$(this).animate({
			'opacity' : 1
		},300);
	},function() {
		var opacity_inactive = $(this).attr("op");
		$(this).animate({
			'opacity' : opacity_inactive
		},300);
	});
	
	//disable page scroll
	// $('.cis_images_row').bind('mousewheel DOMMouseScroll', function(e) {
	//     var scrollTo = null;

	//     if (e.type == 'mousewheel') {
	//         scrollTo = (e.originalEvent.wheelDelta * -1);
	//     }
	//     else if (e.type == 'DOMMouseScroll') {
	//         scrollTo = 40 * e.originalEvent.detail;
	//     }

	//     if (scrollTo) {
	//         e.preventDefault();
	//         $(this).scrollTop(scrollTo + $(this).scrollTop());
	//     }
	// });
	
	//Items drag effect
	$('.cis_images_row img').on('dragstart', function(event) { event.preventDefault(); });
	$('.cis_row_item_overlay').on('dragstart', function(event) { event.preventDefault(); });
	
	//hover animation
	//calculate overlay height
	function cis_calculate_itms_height() {
		$(".cis_row_item").each(function() {
			var $cis_overlay = $(this).find('.cis_row_item_overlay');
			$cis_overlay.css({'visibility' : 'hidden','display' : 'block'});
			//var h = $cis_overlay.height();
			//$cis_overlay.css({'visibility' : 'visible','display' : 'block','height' : '0'}).attr('h',h);
		});
	};
	
	$(".cis_row_item img").load(function() {
		var $this = $(this);
		$this.attr('cis_loaded','loaded');
		var $cis_overlay = $(this).next('.cis_row_item_overlay');
		$cis_overlay.css({'visibility' : 'hidden','display' : 'block'});
		var h = $cis_overlay.height();
		$cis_overlay.css({'visibility' : 'visible','display' : 'block','height' : '0'}).attr('h',h);
		$cis_overlay.attr('cis_animation','enabled');
		
		$this.addClass('cis_loaded');
		cis_make_proccess($this);
	});
	
	function cis_make_proccess($el) {
		var item_width = $el.width();
		$el.parents('.cis_row_item').find('.cis_row_item_loader').animate({
			width: item_width
		},400,'swing',function() {
			$el.parents('.cis_row_item').find('.cis_row_item_loader').fadeOut(200,function() {
				$el.parents('.cis_row_item_inner').hide().removeClass('cis_row_hidden_element').fadeIn(200);
			});
		});
	};
	
	function cis_getRandomArbitary (min, max) {
	    return Math.random() * (max - min) + min;
	};
	
	function cis_calculate_loaders_width() {
		$('.cis_images_holder').each(function() {
			var $wrapper = $(this);
			var wrapper_width = $wrapper.parents('.cis_images_row').width();
			var items_height = $wrapper.find('.cis_row_item_loader').height();
			
			var loader_prepared_width = items_height * 1.5;
			var loader_ratio_sign = Math.random() < 0.5 ? 1 : -1;
			$wrapper.find('.cis_row_item_loader').each(function() {
				var loader_width_calculated = loader_prepared_width + loader_ratio_sign * cis_getRandomArbitary(0,20);
				$(this).width(loader_width_calculated);
				loader_ratio_sign = loader_ratio_sign == 1 ? -1 : 1;
			});
		});
	};
	cis_calculate_loaders_width();
	
	$(".cis_row_item").hover(function() {
		return;

		var animation_enabled = $(this).find('.cis_row_item_overlay').attr('cis_animation');
		if(animation_enabled != 'enabled')
			return;
		
		if(!($(this).parents('.cis_images_holder').hasClass('cis_scrolling'))) {
			var $cis_overlay = $(this).find('.cis_row_item_overlay');
			var overlay_height = parseInt($cis_overlay.attr('h'));
			$cis_overlay.stop().animate({
				height: overlay_height
			},300,'swing');
		}
	},function() {

		return;

		var animation_enabled = $(this).find('.cis_row_item_overlay').attr('cis_animation');
		if(animation_enabled != 'enabled')
			return;
		
		var $cis_overlay = $(this).find('.cis_row_item_overlay');
		$cis_overlay.stop().animate({
			height: 0
		},300,'swing');
	});


// 3.0. updates *******************************************************************************************

// icons functions ***********************************************************************************

function cis_set_items_icons() {

	// remove existing items
	$('.cis_zoom_icon').remove();
	$('.cis_link_icon').remove();

	$wrapper = $(".cis_main_wrapper");
	$wrapper.find(".cis_row_item_overlay").each(function() {
		var $this = $(this);
		var $cis_item = $this.parents(".cis_row_item");

		var cis_popup_event = parseInt($("#cis_popup_open_event").val());
		var cis_link_event = parseInt($("#cis_link_open_event").val());

		if(cis_popup_event == 0 || cis_link_event == 0) {//open popup onclick of button
			cis_make_item_icons($cis_item, cis_popup_event, cis_link_event);
		}
	});

	cis_set_zoom_events();
	cis_set_link_events();
}
// cis_set_items_icons();

function cis_make_item_icons($cis_item, cis_popup_event, link_open_event) {

		var $cis_item_inner = $cis_item.find('.cis_row_item_inner');
		var item_h = parseInt($cis_item_inner.height());
		var item_w = parseInt($cis_item_inner.width());

		// var slider_data = $cis_item.parents('.cis_main_wrapper ').find('.cis_options_data').html();
		// var slider_data_array = slider_data.split(',');

		var icon_w = parseInt($("#cis_icons_size").val());
		var icons_margin = parseInt($("#cis_icons_margin").val());
		var right_offset = parseInt($("#cis_icons_offset").val());
		var top_offset = right_offset;
		var icons_position = parseInt($("#cis_icons_valign").val()) == 0 ? 'top' : 'center';
		var icon_color = parseInt($("#cis_icons_color").val()) == 0 ? 'black' : 'white';
		var icon_animation = parseInt($("#cis_icons_animation").val());

		var overlay_items_vertical_offset = parseInt($("#cis_ov_items_offset").val());
		var overlay_items_middle_offset = parseInt($("#cis_ov_items_m_offset").val());



		if(cis_popup_event == 0) {

			if(icons_position == 'center') {

				var cis_overlay_type = $("#cis_overlay_type").val();

				var total_items_height = icon_w;
				if(cis_overlay_type == 1) {

					var caption_visible = parseInt($("#cis_showreadmore").val());
					if(caption_visible == 1) {
						var cis_caption_height = parseInt($cis_item.find('.cis_row_item_txt_wrapper').height());
						total_items_height = total_items_height + cis_caption_height*1 + overlay_items_vertical_offset*1;
					}

					var button_visible = (cis_popup_event == 2 || link_open_event == 2) ? 1 : 0;
					if(button_visible == 1) {
						var cis_button_height = parseInt($cis_item.find('.cis_btn_wrapper').height());
						total_items_height = total_items_height + cis_button_height*1 + overlay_items_vertical_offset*1;
					}
				}


				var right_position = link_open_event == 0 ? (item_w + 1 * icons_margin) / 2 : (item_w - icon_w) / 2;
				var top_position = (item_h - total_items_height) / 2 + 1*overlay_items_middle_offset;
			}
			else {
				var right_position = link_open_event == 0 ?  icon_w + icons_margin + right_offset : right_offset;
				var top_position = top_offset;
			}
			var zoom_icon_html = '<div class="cis_zoom_icon cis_zoom_icon_hidden_ cis_icon_effect_'+ icon_animation + ' cis_icon_' + icon_color + '" title="Zoom"><div class="cis_zoom_icon_inner "></div></div>';

			$cis_item_inner.append(zoom_icon_html);
			var $cis_zoom_icon = $cis_item_inner.find('.cis_zoom_icon');
			$cis_zoom_icon.css({
				'width' : icon_w,
				'height' : icon_w,
				'top' : top_position,
				'right' : right_position
			});

		}		
		if(link_open_event == 0) {
			var top_position = (item_h - icon_w) / 2;

			if(icons_position == 'center') {
				var cis_overlay_type = $("#cis_overlay_type").val();

				var total_items_height = icon_w;
				if(cis_overlay_type == 1) {

					var caption_visible = parseInt($("#cis_showreadmore").val());
					if(caption_visible == 1) {
						var cis_caption_height = parseInt($cis_item.find('.cis_row_item_txt_wrapper').height());
						total_items_height = total_items_height + cis_caption_height*1 + overlay_items_vertical_offset*1;
					}

					var button_visible = (cis_popup_event == 2 || link_open_event == 2) ? 1 : 0;
					if(button_visible == 1) {
						var cis_button_height = parseInt($cis_item.find('.cis_btn_wrapper').height());
						total_items_height = total_items_height + cis_button_height*1 + overlay_items_vertical_offset*1;
					}
				}

				var right_position = cis_popup_event == 0 ? (item_w - 2 * icon_w  - icons_margin) / 2 : (item_w - icon_w) / 2;
				var top_position = (item_h - total_items_height) / 2 + 1*overlay_items_middle_offset;
			}
			else {
				var right_position = cis_popup_event == 0 ? right_offset : right_offset;
				var top_position = top_offset;
			}
			var link_icon_html = '<div class="cis_link_icon cis_link_icon_hidden_ cis_icon_effect_'+ icon_animation + ' cis_icon_' + icon_color + '" title="Open Link"></div>';

			$cis_item_inner.append(link_icon_html);
			var $cis_link_icon = $cis_item_inner.find('.cis_link_icon');
			$cis_link_icon.css({
				'width' : icon_w,
				'height' : icon_w,
				'top' : top_position,
				'right' : right_position
			});
		}
	};

	function cis_make_items_icons_animation_preview() {
		$('.cis_zoom_icon_hidden_').addClass('cis_zoom_icon_hidden').removeClass('cis_zoom_icon_hidden_');
		$('.cis_link_icon_hidden_').addClass('cis_link_icon_hidden').removeClass('cis_link_icon_hidden_');

		setTimeout(function() {
			$('.cis_zoom_icon_hidden').removeClass('cis_zoom_icon_hidden');
			$('.cis_link_icon_hidden').removeClass('cis_link_icon_hidden');
		},600);
	}

	function cis_set_zoom_events() {
		$('.cis_row_item_inner').on('mouseenter', '.cis_zoom_icon', function() {
			$(this).addClass('cis_zoom_icon_active');
		});		
		$('.cis_row_item_inner').on('mouseleave', '.cis_zoom_icon', function() {
			$(this).removeClass('cis_zoom_icon_active');
		});
	};

	function cis_set_link_events() {
		$('.cis_images_row').on('mouseenter', '.cis_link_icon', function() {
			$(this).addClass('cis_link_icon_active');
		});		
		$('.cis_images_row').on('mouseleave', '.cis_link_icon', function() {
			$(this).removeClass('cis_link_icon_active');
		});
	};

	// button functions ****************************************************************

	function cis_check_button_rule() {
		var cis_popup_event = parseInt($("#cis_popup_open_event").val());
		var cis_link_event = parseInt($("#cis_link_open_event").val());

		if(cis_popup_event == 2 || cis_link_event == 2) {
			$(".creative_btn").show().css('display','inline-block');
		}
		else {
			$(".creative_btn").hide();
		}
	}


	//  icons live preview functions **************************************************

	$("#cis_icons_size").change(function() {
		cis_render_overlay_items();
	});
	$("#cis_icons_margin").change(function() {
		cis_set_items_icons();
	});
	$("#cis_icons_offset").change(function() {
		cis_render_overlay_items();
	});
	$("#cis_icons_valign").change(function() {
		cis_render_overlay_items();
	});
	$("#cis_icons_color").change(function() {
		cis_set_items_icons();
	});
	$("#cis_icons_animation").change(function() {
		cis_set_items_icons();
		cis_make_items_icons_animation_preview();
	});
	
	$("#cis_popup_open_event").change(function() {
		cis_render_overlay_items();
		cis_check_button_rule();
	});	
	$("#cis_link_open_event").change(function() {
		cis_render_overlay_items();
		cis_check_button_rule();
	});

	$("#cis_ov_items_offset").change(function() {
		cis_render_overlay_items();
	});
	$("#cis_ov_items_m_offset").change(function() {
		cis_render_overlay_items();
	});

	// font family
	$("#cis_font_family").change(function() {
		var val = $(this).val();
		var font_name = $(this).find('option:selected').html();
		var cis_font_ident = 'cis-googlewebfont-';

		if(val.indexOf(cis_font_ident) > -1) {
			val = val.replace(cis_font_ident, '');
			val = val.replace(/ /g, '+');
			var font_href = 'http://fonts.googleapis.com/css?family=' + val;

			//load new css
			$("<link/>", {
			   rel: "stylesheet",
			   type: "text/css",
			   href: font_href
			}).appendTo("head");

			var google_font_war = font_name + ', sans-serif';
			$(".cis_row_item_txt_wrapper ").css('fontFamily' , google_font_war);
		}
		else 
			$(".cis_row_item_txt_wrapper ").css('fontFamily' , val);
	});
	$("#cis_button_font_family").change(function() {
		var val = $(this).val();
		var font_name = $(this).find('option:selected').html();
		var cis_font_ident = 'cis-googlewebfont-';

		if(val.indexOf(cis_font_ident) > -1) {
			val = val.replace(cis_font_ident, '');
			val = val.replace(/ /g, '+');
			var font_href = 'http://fonts.googleapis.com/css?family=' + val;

			//load new css
			$("<link/>", {
			   rel: "stylesheet",
			   type: "text/css",
			   href: font_href
			}).appendTo("head");

			var google_font_war = font_name + ', sans-serif';
			$(".cis_btn_wrapper ").css('fontFamily' , google_font_war);
		}
		else 
			$(".cis_btn_wrapper ").css('fontFamily' , val);
	});

	// overlay type
	$("#cis_overlay_type").change(function() {
		if($(this).val() == 0) { // bottom fixed
			$('.cis_row_item_overlay').removeClass('cis_height_100_perc').addClass('cis_height_auto');
		}
		else { // full size
			$('.cis_row_item_overlay').removeClass('cis_height_auto').addClass('cis_height_100_perc');
		}

		cis_render_overlay_items();	
	});

	function cis_render_overlay_items() {

		$wrapper = $(".cis_main_wrapper");
		$wrapper.find(".cis_row_item_overlay").each(function() {
			var $this = $(this);
			var $cis_item = $this.parents(".cis_row_item");

			cis_render_overlay_item($cis_item);

		});
	}

	function cis_render_overlay_item($cis_item) {
		var cis_overlay_type = $("#cis_overlay_type").val();
		var cis_popup_event = parseInt($("#cis_popup_open_event").val());
		var link_open_event = parseInt($("#cis_link_open_event").val());

		var item_h = parseInt($('.cis_row_item_inner').height());

		var overlay_items_vertical_offset = parseInt($("#cis_ov_items_offset").val());
		var overlay_items_middle_offset = parseInt($("#cis_ov_items_m_offset").val());

		var icons_position = parseInt($("#cis_icons_valign").val());

		if(cis_overlay_type == 0) { // bottom fixed
			$('.cis_row_item_txt_wrapper').removeClass('cis_position_absolute').removeClass('cis_align_center').find('.cis_row_item_overlay_txt').removeClass('cis_margin_0');
			$('.cis_btn_wrapper').removeClass('cis_position_absolute').removeClass('cis_align_center').find('.creative_btn').removeClass('cis_margin_0').removeClass('cis_float_none');

			$('.cis_row_item_txt_wrapper').attr("style","");
			$("#cis_font_family").trigger('change');
		} else { // full size
			$('.cis_row_item_txt_wrapper').addClass('cis_position_absolute').addClass('cis_align_center').find('.cis_row_item_overlay_txt').addClass('cis_margin_0');
			$('.cis_btn_wrapper').addClass('cis_position_absolute').addClass('cis_align_center').find('.creative_btn').addClass('cis_margin_0').addClass('cis_float_none');


		}

		// render icons
		cis_set_items_icons();


		// render caption and button
		if(cis_overlay_type == 1) {
			// render caption
			var caption_visible = parseInt($("#cis_showreadmore").val());
			if(caption_visible == 1) {
				
				$cis_item.find('.cis_txt_inner').addClass('cis_h_padding_set'); // add horizontal padding
				var cis_caption_height = parseInt($cis_item.find('.cis_row_item_txt_wrapper').height());
				var total_items_height = cis_caption_height;

				// check if icon(s) visible
				var icon_visible = ((cis_popup_event == 0 || link_open_event == 0) && icons_position == 1) ? 1 : 0;
				if(icon_visible == 1) {
					var icon_w = parseInt($("#cis_icons_size").val());
					total_items_height = total_items_height + icon_w*1 + overlay_items_vertical_offset*1;
				}

				// check if button visible
				var button_visible = (cis_popup_event == 2 || link_open_event == 2) ? 1 : 0;
				if(button_visible == 1) {
					var cis_button_height = parseInt($cis_item.find('.cis_btn_wrapper').height());
					total_items_height = total_items_height + cis_button_height*1 + overlay_items_vertical_offset*1;
				}

				//calculate top position
				var top_offset = ((item_h - total_items_height) / 2) + 1*overlay_items_middle_offset;
				if(icon_visible == 1)
					top_offset = top_offset + 1*icon_w + 1*overlay_items_vertical_offset;

				// set css
				$cis_item.find('.cis_row_item_txt_wrapper').css('top',top_offset);

			}
			// render button
			var button_visible = (cis_popup_event == 2 || link_open_event == 2) ? 1 : 0;
			if(button_visible == 1) {
				var cis_button_height = parseInt($('.cis_btn_wrapper').height());
				var total_items_height = cis_button_height;

				// check if icon(s) visible
				var icon_visible = ((cis_popup_event == 0 || link_open_event == 0) && icons_position == 1) ? 1 : 0;
				if(icon_visible == 1) {
					var icon_w = parseInt($("#cis_icons_size").val());
					total_items_height = total_items_height + icon_w*1 + overlay_items_vertical_offset*1;
				}

				var caption_visible = parseInt($("#cis_showreadmore").val());
				if(caption_visible == 1) {
					var cis_caption_height = parseInt($cis_item.find('.cis_row_item_txt_wrapper').height());
					total_items_height = total_items_height + cis_caption_height*1 + overlay_items_vertical_offset*1;
				}

				//calculate top position
				var top_offset = ((item_h - total_items_height) / 2) + 1*overlay_items_middle_offset;
				if(icon_visible == 1)
					top_offset = top_offset + 1*icon_w + 1*overlay_items_vertical_offset;
				if(caption_visible == 1)
					top_offset = top_offset + 1*cis_caption_height + 1*overlay_items_vertical_offset;

				// set css
				$cis_item.find('.cis_btn_wrapper').css('top',top_offset);
			}
		}
		// end render caption and button
	}

	setTimeout(function() {
		cis_render_overlay_items();
	},800);

	// show / hide cutions
	$("#cis_showreadmore").change(function() {
		var v = parseInt($(this).val());
		if(v == 0)
			$('.cis_row_item_txt_wrapper').addClass('cis_display_none');
		else 
			$('.cis_row_item_txt_wrapper').removeClass('cis_display_none');

		cis_render_overlay_items();

	});
	// font effect
	$("#cis_font_effect").change(function() {
		var v = $(this).val();
		var new_class = 'cis_txt_inner ' + v;
		$('.cis_txt_inner').attr("class",new_class);
	});


	
})
})(creativeJ);