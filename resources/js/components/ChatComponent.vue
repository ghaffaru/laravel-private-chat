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
    }
}
</script>
<style>
a {
    text-decoration: none;
}
</style>
