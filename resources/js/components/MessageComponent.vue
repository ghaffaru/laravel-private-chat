<template>
        <b-card>
            <b-card-header>
                {{ friend.name }}
                 <span v-if="blocked" style="color: red">You have blocked this user</span>
                <b-icon-x style="float: right" @click="closeChat(friend)"></b-icon-x>


                <b-dropdown variant="link" no-caret style="float: right"  toggle-class="text-decoration-none" class="mr-0">
                    <template #button-content>
                        <b-icon-three-dots-vertical   ></b-icon-three-dots-vertical>
                    </template>
                    <b-dropdown-item @click="blocked = true" v-if="!blocked">Block</b-dropdown-item>

                    <b-dropdown-item @click="blocked = false" v-else>Unblock</b-dropdown-item>
                    <b-dropdown-item @click="clearChats">Clear</b-dropdown-item>
                </b-dropdown>
            </b-card-header>

            <b-card-body  class="chat-box" v-chat-scroll>
                <b-card-text v-for="message in messages" :key="message.id" :class="{'text-right': message.type == 0, 'text-success': message.read_at != null && message.type == 0}">
                    <span>{{ message.message }}</span><br>
                    <span style="font-size: 8px">{{ message.read_at}}</span>
<!--                    <span>{{ message.message }}</span>-->
                </b-card-text>
            </b-card-body>

            <b-form @submit.prevent="send">
                <b-card-footer>
                    <b-form-group>
                        <b-form-input type="text" placeholder="Type your message" v-model="message" :disabled="blocked" @click="send"></b-form-input>
                    </b-form-group>

                </b-card-footer>

            </b-form>

        </b-card>
</template>
<script>
export default {
    props: ['friend'],
    data() {
        return {
            message: '',
            messages: [],
            blocked: false
        }
    },
    methods: {
        getChats() {
            axios.get(`/chats/${this.friend.session.id}`)
                .then(res => {
                    this.messages = res.data.data;
                })
        },

        send() {
            if (this.message.length) {
                this.messages.push({message: this.message, type: 0, read_at: null});
                axios.post(`/chat/`, {
                    'session_id': this.friend.session.id,
                    'message': this.message,
                    'receiver_id': this.friend.id
                }).then(res => {
                    console.log(res.data)
                    this.messages[this.messages.length - 1].id = res.data.chat.id;
                    this.message = ''
                })
                .catch(err => {
                    console.log(err.response.data)
                })
            }
        },
        closeChat(friend) {
            this.$emit('closeChat', friend);
        },
        read() {
            axios.patch(`/session/${this.friend.session.id}/mark-as-read`)
                .then(res => console.log(res));
        },
        clearChats() {
            this.messages = [];
            axios.get(`/session/${this.friend.session.id}/clear`)
                .then(res => console.log(res))
        }
    },
    mounted() {
        this.read();
        this.getChats();

        Echo.private(`chat.${this.friend.session.id}`)
            .listen('PrivateChatEvent', e => {
                this.friend.session.open ? this.read() : '';
                this.messages.push({message: e.content, type: 1 })
            })

        Echo.private(`chat.${this.friend.session.id}`)
            .listen('MessageReadEvent', e => {
                this.messages.forEach(msg => {
                    msg.id === e.chat.id ? msg.read_at = e.chat.read_at : '';
                })
            })
    }
}
</script>
<style>
.chat-box {
    height: 400px;
    overflow-y: scroll;
}
</style>
