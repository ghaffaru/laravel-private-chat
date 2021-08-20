<template>
    <div>
        <b-row>
            <b-col md="4">
                <b-card>
                    <b-card-header>Friends</b-card-header>
                    <b-card-body>
                        <b-list-group>
                            <b-list-group-item v-for="friend in friends" :key="friend.id">
                                <a href="" @click.prevent="openChat(friend)">{{ friend.name }}</a>
                                <b-icon-circle-fill style="float:right;" variant="success" v-if="friend.online"></b-icon-circle-fill>
                            </b-list-group-item>
                        </b-list-group>
                    </b-card-body>

                </b-card>
            </b-col>
            <b-col md="8">
                    <span v-for="friend in friends" :key="friend.id" v-if="friend.session">
                        <message-component :friend="friend" v-if="friend.session.open" @closeChat="friend.session.open = false"></message-component>
                    </span>
            </b-col>
        </b-row>


    </div>
</template>
<script>
import MessageComponent from "./MessageComponent";
export default {
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

            })
        },
        openChat(friend) {
            if (friend.session?.open) {
                this.friends.forEach(f => f.session.open = false);
                friend.session.open = true;
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
