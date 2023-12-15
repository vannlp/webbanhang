require('./bootstrap');
require('./slider');
require('./toast');

import { createApp, ref } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';
import ProfileContent from "./components/ProfileContent.vue";
import handleEvent from './components/handleEvent.vue';
import Media from "./components/Media.vue";
import axios from 'axios';
import 'flowbite';

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
// Initialization for ES Users
import {
    Carousel,
    initTE,
  } from "tw-elements";
  
initTE({ Carousel });
// Vuetify
// import 'vuetify/styles'
// import { createVuetify } from 'vuetify'
// import * as components from 'vuetify/components'
// import * as directives from 'vuetify/directives'

let token = document.head.querySelector('meta[name="csrf-token"]').content;
let url = document.head.querySelector('meta[name="url"]').content;

if (!token) {
    console.error(
        'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
    );
}

let jwt_token = document.head.querySelector('meta[name="jwt_token"]').content;
const apis = axios.create({
    baseURL: url,
    headers: {
        Authorization : `Bearer ${jwt_token}`
    }
})
window.apis = apis;
window.jwt_token = jwt_token;
window.token = token;
// $(document).ready(function() {
//     let content = document.querySelector("#content");
//     console.log(content);
// })

// const vuetify = createVuetify({
//     components,
//     directives,
// })

window.app = createApp({
    components: {
        ExampleComponent,
        ProfileContent,
        handleEvent,
        Media
    },
    setup() {
        return {
            count: ref(0),
            csrfToken: token,
            apis: apis
        }
    },
    data() {
        return {
            apis: apis
        }
    },
})
// .use(vuetify)

// app.use(BootstrapVue);
// app.use(IconsPlugin);
