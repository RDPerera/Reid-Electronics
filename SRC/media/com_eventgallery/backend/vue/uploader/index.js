import Vue from 'vue';
import Uploader from './Uploader.vue';
require('./_Uploader.less');

document.addEventListener('DOMContentLoaded', () =>{
    let rootId = 'uploader';
    if (document.getElementById(rootId)) {
        new Vue({
            el: '#' + rootId,
            mounted: function() {
                this.id = this.$el.getAttribute('data-id');
            },
            render: function (createElement) {
                return createElement(Uploader, {
                    props: {
                        maxFileSize: this.$el.getAttribute('data-max-file-size'),
                        uploadUrl: this.$el.getAttribute('data-upload-url'),
                        i18n: {
                            COM_EVENTGALLERY_EVENT_UPLOAD_FILES_TO_UPLOAD: this.$el.getAttribute('data-i18n-COM_EVENTGALLERY_EVENT_UPLOAD_FILES_TO_UPLOAD'),
                            COM_EVENTGALLERY_EVENT_UPLOAD_PENDING: this.$el.getAttribute('data-i18n-COM_EVENTGALLERY_EVENT_UPLOAD_PENDING'),
                            COM_EVENTGALLERY_EVENT_UPLOAD_FINISHED: this.$el.getAttribute('data-i18n-COM_EVENTGALLERY_EVENT_UPLOAD_FINISHED')
                        }
                    }
                })
            }
        });
    }

});