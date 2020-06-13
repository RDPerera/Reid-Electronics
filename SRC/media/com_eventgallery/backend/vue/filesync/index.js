import Vue from 'vue';
import Filesync from './Filesync.vue';
import {mapLocalizationData} from '../helper/LocalizationMapper'

document.addEventListener('DOMContentLoaded', () =>{
    let rootId = 'filesync';

    if (document.getElementById(rootId)) {
        new Vue({
            el: '#' + rootId,
            mounted: function() {
                this.id = this.$el.getAttribute('data-id');
            },
            render: function (createElement) {
                return createElement(Filesync, {
                    props: {
                        loadFoldersUrl: this.$el.getAttribute('data-load-folders-url'),
                        fileSyncUrl: this.$el.getAttribute('data-file-sync-url'),
                        folderSyncUrl: this.$el.getAttribute('data-folder-sync-url'),
                        csrfToken: this.$el.getAttribute('data-csrf-token'),
                        fileBatchSize: parseInt(this.$el.getAttribute('data-file-batch-size')),
                        maxParallelRequests: 4,
                        i18n: mapLocalizationData(this.$el),
                    },
                })
            }
        });
    }

});
