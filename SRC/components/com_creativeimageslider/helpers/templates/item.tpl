<div class="cis_row_item cis_item_{ITEM_ID}"
     id="cis_item_{ITEM_ID}"
     data-cis_popup_link="{POPUP_IMG_SRC}"
     data-item_id="{ITEM_ID}"
>
    <div class="cis_popup_caption" style="display: none !important;">{ITEM_CAPTION}</div>
    <div class="cis_row_item_loader {LOADER_COLOR_CLASS}" style="height: {ITEM_HEIGHT}px !important;"></div>
    <div class="cis_row_item_inner cis_row_hidden_element">
        <img src="{IMG_PATH}" class="cis_img_item"  style="height: {ITEM_HEIGHT}px !important;" alt="{ITEM_NAME}" title="{ITEM_NAME}" />
        <div class="cis_row_item_overlay {OVERLAY_CLASS}"
             data-cis_popup_event="{POPUP_EVENT}"
             data-link_open_event="{LINK_EVENT}"
             data-cis_click_url="{CLICK_URL}"
             data-cis_click_target="{CLICK_TARGET}"
             data-cis_button_visible="{BUTTON_VISIBLE}"
        >
            {ITEM_NAME_HTML}
            {ITEM_BUTTON_HTML}
        </div>
    </div>
</div>