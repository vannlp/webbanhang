<template>
    <slot :onclick="onclick">

    </slot>

</template>


<script>
export default {
    name: 'handle-event',
    props: {
        eventFuntion: {
            type: Object,
        }
    },

    methods: {
        onclick: function (event_funtion) {
            // alert(123);
            event_funtion();
        }
    }
}
</script>