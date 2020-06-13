<style lang="less" scoped>

    .folders {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        list-style: none;

        li {
            padding: 10px;
            margin: 10px;
            border: 5px solid #EEE;
        }
    }

    .blocked {
        opacity: 0.5;
    }
</style>

<template>
    <ul class="folders" v-bind:class="{blocked: blocked}">
        <folder v-for="folder in sortedFolders"  v-bind:key="folder.foldername" :folder="folder"></folder>
    </ul>
</template>

<script>

    import Folder from "./Folder.vue";

    export default {
        components: {
            Folder
        },
        props: {
            folders: null,
            blocked: null
        },
        computed: {
            sortedFolders: function(){
                return this.folders.slice().sort((a, b) => {
                    return a.foldername.localeCompare(b.foldername);
                });
            }
        }

    };
</script>
