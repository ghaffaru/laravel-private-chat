require('./bootstrap');

require('alpinejs');

import Vue from 'vue';
import Vuesax from 'vuesax';
import VueChatScroll from 'vue-chat-scroll';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import ChatComponent from './components/ChatComponent';

import 'vuesax/dist/vuesax.css';

Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
Vue.use(Vuesax, {
    // options here
});
Vue.use(VueChatScroll);


const app = new Vue({
    el: '#app',
    components: {ChatComponent}
});
