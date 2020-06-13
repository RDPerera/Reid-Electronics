<style scoped lang="less">
    @import "_Uploader.less";
</style>

<template>
    <div class="Uploader">
        <label for="fileselect">{{i18n.COM_EVENTGALLERY_EVENT_UPLOAD_FILES_TO_UPLOAD}}:</label>
        <input id="fileselect" type="file" multiple="multiple" v-on:change="addFiles">

        <div class="eg-progressbar" v-if="pendingFiles.length + inProgressFiles.length > 0">
            <div class="eg-progressbar-state" v-bind:style="{width: progress + '%'}"></div>
        </div>

        <div class="eg-bo-row">

            <div v-if="pendingFiles.length > 0" class="eg-bo-column3">
                <h2 >{{i18n.COM_EVENTGALLERY_EVENT_UPLOAD_PENDING}}</h2>
                <ul id="pending">
                    <li v-for="pendingFile in revertedPendingFiles">
                        {{ pendingFile.name }}
                    </li>
                </ul>
            </div>

            <div v-if="finishedFilesContent.length > 0 || inProgressFiles.length > 0 || failedFilesContent.length > 0" class="eg-bo-column9">
                <h2>{{i18n.COM_EVENTGALLERY_EVENT_UPLOAD_FINISHED}}</h2>
                <ul id="finished" class="eg-bo-row" style="flex-wrap: wrap">
                    <li v-for="inProgressFile in inProgressFiles" class="eg-bo-column3">
                        <div>{{ inProgressFile.name }}</div>
                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    </li>
                    <li v-for="failedFileContent in failedFilesContent" class="eg-bo-column3" v-html="failedFileContent.content" v-bind:key="failedFileContent.id">
                    </li>
                    <li v-for="finishedFileContent in revertedFinishedFilesContent" v-html="finishedFileContent.content" v-bind:key="finishedFileContent.id" class="eg-bo-column3">
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import {removeElement} from '../../../common/js/Helpers';

    export default {
        props: {
            i18n: null,
            uploadUrl: null,
            maxFileSize: null
        },
        computed: {
            progress: function() {
                let total = this.pendingFiles.length + this.inProgressFiles.length + this.finishedFilesContent.length + this.failedFilesContent.length;
                let progress = (total - this.pendingFiles.length) / total * 100;
                return progress;
            },
            revertedPendingFiles: function() {
                  return this.pendingFiles.slice().reverse();
            },
            revertedFinishedFilesContent: function() {
                return this.finishedFilesContent.slice().reverse();
            }
        },
        data() {
            return {
                pendingFiles: [],
                inProgressFiles: [],
                finishedFilesContent: [],
                failedFilesContent: [],
            };
        },
        methods: {
            addFiles: function (event) {
                let newFiles = event.target.files || event.dataTransfer.files;
                for (let i = 0; i<newFiles.length; i++) {
                    let f = newFiles[i];
                    this.pendingFiles.push(f);
                }

                this.upload();
            },
            upload: function() {
                if (this.pendingFiles.length === 0) {
                    return;
                }
                let file = this.pendingFiles.pop();
                let data = new FormData();
                let xhr = new XMLHttpRequest();

                if (xhr.upload && file.size <= this.maxFileSize) {

                    this.inProgressFiles.push(file);

                    // file received/failed
                    xhr.onreadystatechange = () => {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                this.finishedFilesContent.push({id: this.finishedFilesContent.length,'content':xhr.responseText});
                            } else {
                                this.failedFilesContent.push({id: this.failedFilesContent.length,'content': xhr.responseText});
                            }
                            removeElement(this.inProgressFiles, file);
                            this.upload();
                        }
                    };

                    data.append('file', file, file.name);
                    xhr.open("POST", this.uploadUrl, true);
                    xhr.send(data);

                } else {
                    console.log("invalid file, will not try to upload it");
                    this.upload();
                }
            }
        }
    };
</script>
