<!-- Creative Image Slider Start -->
<div class="cis_main_wrapper_canvas">
    <div id="cis_slider_{SLIDER_ID}_{MODULE_ID}"
         data-cis_base="{MAIN_PATH}"
         data-id="cis_slider_{SLIDER_ID}_{MODULE_ID}"
         data-slider_id="{SLIDER_ID}"
         data-module_id="{MODULE_ID}"
         data-cis_overlay_animation_type="{OVERLAY_ANIMATION_TYPE}"
         data-cis_overlay_type="{OVERLAY_TYPE}"
         data-inf_scroll_enabled="{INF_SCROLL_ENABLED}"
         data-mouse_scroll_enabled="{MOUSE_SCROLL_ENABLED}"
         data-cis_touch_enabled="{TOUCH_ENABLED}"
         data-item_correction_enabled="{ITEM_CORRECTION_ENABLED}"
         data-cis_popup_event="{POPUP_EVENT}"
         data-link_open_event="{LINK_EVENT}"
         data-slider_full_size="{SLIDER_FULL_SIZE}"
         class="cis_main_wrapper {WRAPPER_CLASS}"
    >
            <div class="cis_images_row">
                <!-- arrows start -->
                <img class="cis_button_left" src="{LEFT_BUTTON_SRC}" alt="" title="" />
                <img class="cis_button_right" src="{RIGHT_BUTTON_SRC}" alt="" title="" />
                <!-- arrows end -->
                <!-- options data start -->
                <div class="cis_arrow_data" style="display: none !important;">{CIS_ARROW_DATA}</div>
                <div class="cis_moving_data" style="display: none !important;">{CIS_MOVING_DATA}</div>
                <div class="cis_popup_data" style="display: none !important;">{CIS_POPUP_DATA}</div>
                <div class="cis_options_data" style="display: none !important;">{CIS_OPTIONS_DATA}</div>
                <!-- options data end -->
                <!-- images holder start -->
                <div class="cis_images_holder" style="height: {ITEMS_HEIGHT}px !important;">
                    {ITEMS}
                </div>
                <!-- images holder end -->
            </div>
    </div>
</div>
{STYLES}
{GOOGLE_FONTS}
{JAVASCRIPT}
<!-- Creative Image Slider End -->
