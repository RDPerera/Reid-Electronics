<style scoped>
    .container {
        width: 100%;
        margin: 20px auto;
    }
    .progressbar {
        counter-reset: step;
    }
    .progressbar li {
        list-style-type: none;
        width: 33.333%;
        float: left;
        font-size: 12px;
        position: relative;
        text-align: center;
        color: #7d7d7d;
    }

    .progressbar li span.title {
        text-transform: uppercase;
    }

    .progressbar li:before {
        width: 30px;
        height: 30px;
        content: counter(step);
        counter-increment: step;
        line-height: 30px;
        border: 2px solid #7d7d7d;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background-color: white;
    }
    .progressbar li:after {
        width: 100%;
        height: 2px;
        content: '';
        position: absolute;
        background-color: #7d7d7d;
        top: 15px;
        left: -50%;
        z-index: -1;
    }
    .progressbar li:first-child:after {
        content: none;
    }
    .progressbar li.active {
        color: green;
    }
    .progressbar li.active:before {
        border-color: #55b776;
    }
    .progressbar li.active + li:after {
        background-color: #55b776;
    }

    .progressbar span {
        display: block;
        margin-bottom: 10px;
    }

</style>

<template>
    <div class="container">
        <ul class="progressbar">
            <li v-bind:class="{active: true}">
                <span class="title">{{i18n_labelStep1}}</span>
            </li>
            <li v-bind:class="{active: numberOfSelectedFolders > 0}">
                <span class="title">{{i18n_labelStep2}}</span>
                <span v-if="numberOfSelectedFolders === 0">{{i18n_hintStep2}}</span>
                <button class="btn btn-primary sync-folders" :disabled="running || numberOfSelectedFolders===0" v-on:click="$emit('sync-folders')">{{i18n_labelButtonStep2}} ({{numberOfSelectedFolders}})</button>
            </li>
            <li v-bind:class="{active: isReadyForSyncFiles > 0}">
                <span class="title">{{i18n_labelStep3}}</span>
                <span v-if="isReadyForSyncFiles">{{i18n_hintStep3}}</span>
                <button class="btn btn-primary sync-files" :disabled="running || !isReadyForSyncFiles" v-on:click="$emit('sync-files')">{{i18n_labelButtonStep3}} ({{numberOfSelectedFiles}})</button>
            </li>
        </ul>


    </div>
</template>

<script>
    export default {
        props: {
            running: null,
            isReadyForSyncFiles: null,
            numberOfSelectedFolders: null,
            numberOfSelectedFiles: null,
            i18n_labelStep1: null,
            i18n_labelStep2: null,
            i18n_hintStep2: null,
            i18n_labelButtonStep2: null,
            i18n_labelStep3: null,
            i18n_hintStep3: null,
            i18n_labelButtonStep3: null,
        }
    };
</script>
