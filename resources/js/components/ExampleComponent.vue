

<template>
    <div>
        <!-- <h1>Hello, {{ user.username }}!</h1> -->
        <button class="btn btn-primary" @click="handleClick">Click</button>
        <v-btn color="primary">
            Button
        </v-btn>
    </div>
</template>

<script>
export default {
    // Các phương thức, dữ liệu và vòng đời của component Vue
    name: "ExampleComponent",
    props: {
        name: {
            type: Object,
            required: true
        },
        user: {
            type: Object,
            require: false
        }
    },
    methods: {
        handleClick: async function () {
            try {
                let res = await window.apis.get("api/test-api");
                // let res = this.csrfToken;
                console.log(this.count);
            }
            catch (error) {
                console.log(error);
            }
        }
    },
}
</script>
