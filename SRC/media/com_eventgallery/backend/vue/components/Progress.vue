<style scoped>
    .eg-progressbar-state {
        height: 20px;
        text-align: center;
    }
</style>

<template>
       <div class="eg-progressbar" >
            <div class="eg-progressbar-state" v-bind:style="{width: progress + '%'}">
                <template v-if="remaining>0"></template>
            </div>
        </div>

</template>

<script>
    export default {
        props: {
            remaining: null,
            total: null,
        },
        computed: {
            progress: function() {
                return (this.total-this.remaining) / this.total * 100;
            }
        }

    };
</script>
