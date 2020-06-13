<style scoped lang="less">
    #errorMessages {
        pre {
            max-height: 50vh;
            overflow-y: scroll;
        }
        div {
            color: #D8000C;
            background-color: #FFD2D2;
            padding: 12px;
            margin: 10px 0;
        }
    }
</style>

<template>
    <div id="errorMessages" v-if="errorMessages.length>0 || failedFolders.length>0">
        <h2>{{headline}}</h2>
        <pre v-if="errorMessages.length>0"><template v-for="message in reversedErrorMessages">{{message}}</template></pre>
        <div v-for="failedFolder in failedFolders">
            {{failedFolder.error}}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            headline: null,
            errorMessages: null,
            failedFolders: null,
        },
        computed: {
            reversedErrorMessages: function() {
                return this.errorMessages.slice().reverse();
            }
        }

    };
</script>
