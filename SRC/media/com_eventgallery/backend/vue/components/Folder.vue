<style scoped>
    li {
        display: flex;
        align-items: center;
    }

    input[type=checkbox] {
        transform: scale(1.5);
    }

    .description {
        margin: 10px;
    }

    .filecount {
        width: 100%;
        text-align: center;
        font-size: 1.2em;
        padding: 5px;
    }

</style>

<template>
    <li @click="folder.checked = !folder.checked" :style="{backgroundColor: backgroundColor}">
        <div><input type="checkbox" v-model="folder.checked" ></div>
        <div class="description">
            <div>{{folder.foldername}}</div>
            <div><strong>{{status}}</strong></div>
            <div class="filecount" v-if="folder.files">({{numberOfFilesLeft}} / {{folder.files.length}})</div>
        </div>
    </li>
</template>

<script>
    export default {
        props: {
            folder: null
        },
        methods: {

        },
        computed: {
            folderid: function() {
                return 'folder_' + this.folder.foldername;
            },
            numberOfFilesLeft: function () {
                return this.folder.files.filter((file => {return file.status !== 'sync'})).length;
            },
            status: function() {
                return this.folder.status;
            },
            backgroundColor: function() {
                if (!this.folder || !this.folder.status) return "";

                if (this.folder.status === 'sync') {
                    return "lightgreen";
                }
                if (this.folder.status === 'nosync') {
                    return "#DDD";
                }
                if (this.folder.status === 'deleted') {
                    return 'orange';
                }

            }
        }

    };
</script>
