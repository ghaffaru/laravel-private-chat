<template>
    <div>
        <b-row>
            <b-col md="4">
                <b-card>
                    <b-card-header>Friends</b-card-header>
                    <b-card-body>
                        <b-list-group>
                            <b-list-group-item v-for="friend in friends" :key="friend.id">
                                <a href="" @click.prevent="openChat(friend)">{{ friend.name }}
                                <span class="text-danger" v-if="friend.session && friend.session.unReadCount > 0">{{ friend.session.unReadCount }}</span></a>
                                <b-icon-circle-fill style="float:right;" variant="success" v-if="friend.online"></b-icon-circle-fill>
                            </b-list-group-item>
                        </b-list-group>
                    </b-card-body>

                </b-card>
            </b-col>
            <b-col md="8">
                    <span v-for="friend in friends" :key="friend.id" v-if="friend.session">
                        <message-component :friend="friend" v-if="friend.session.open" @closeChat="friend.session.open = false" :auth="auth"></message-component>
                    </span>
            </b-col>
        </b-row>


    </div>
</template>
<script>
import MessageComponent from "./MessageComponent";
export default {
    props: ['auth'],
    data() {
        return {
            friends: []
        }
    },
    components: {MessageComponent},
    methods: {
        getFriends() {
            axios.get('/friends').then(res => {
                this.friends = res.data.data;
                this.friends.forEach(friend => {
                    if (friend.session) {
                        this.listenForEverySession(friend)
                    }
                })
            })
        },
        listenForEverySession(friend) {
            Echo.private(`chat.${friend.session.id}`)
                .listen('PrivateChatEvent', e => {
                    if (!friend.session.open) {
                        friend.session.unReadCount++;
                    }
                })
        },
        openChat(friend) {
            if (friend.session) {
                this.friends.forEach(f => {
                    f.session ? f.session.open = false : ''
                });
                friend.session.open = true;
                friend.session.unReadCount = 0;
            } else {
                axios.post('/create-session', {friend_id: friend.id})
                    .then(res => {
                        friend.session = res.data.data;
                        friend.session.open = true;
                    })
            }

        }
    },
    mounted() {
        this.getFriends();

        Echo.channel('chat').listen('SessionEvent', e => {
            let friend = this.friends.find(f => f.id === e.session_by);
            friend.session = e.session;
            this.listenForEverySession(friend);
        });
        Echo.join('online')
            .here(users => {
                this.friends.forEach(f => {
                    users.forEach(user => {
                        if (user.id === f.id) f.online = true;
                    })
                })
            })
            .joining(user => {
                let friend = this.friends.find(f => f.id === user.id)
                friend.online = true;
            })
            .leaving(user => {
                let friend = this.friends.find(f => f.id === user.id)
                friend.online = false;
            })
    }
}
</script>
<style>
a {
    text-decoration: none;
}
</style>
