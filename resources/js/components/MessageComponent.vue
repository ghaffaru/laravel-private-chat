<template>
        <b-card>
            <b-card-header>Chats</b-card-header>

            <b-card-body  class="chat-box" v-chat-scroll>
                <b-card-text v-for="message in messages" :key="message">
                    {{ message }}
                </b-card-text>
            </b-card-body>

            <b-form @submit.prevent="send">
                <b-card-footer>
                    <b-form-group>
                        <b-form-input type="text" placeholder="Type your message" v-model="message"></b-form-input>
                    </b-form-group>

                </b-card-footer>

            </b-form>

        </b-card>
</template>
<script>
export default {
    data() {
        return {
            message: '',
            messages: []
        }
    },
    methods: {
        send() {
            if (this.message.length) {
                this.messages.push(this.message);
            }
        }
    }
}
</script>
<style>
.chat-box {
    height: 400px;
    overflow-y: scroll;
}
</style>
