<template>
<div :style="{ 'display': display, textAlign: 'center', ...style }" :class="class" v-if="display">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="spinner-border text-primary" role="status" v-else>
  <span class="sr-only">Loading...</span>
</div>
</template>

<script>
export default {
    name: "loading",
    data() {
        return {
            
        }
    },

    props: {
        display: {
            type: String,
        },
        style: {
            type: Object,
            default: {}
        },
        class: {
            type: String,
            default: ""
        }
    },

    methods: {
        
    },
}

</script>