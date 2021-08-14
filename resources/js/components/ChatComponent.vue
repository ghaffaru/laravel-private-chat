<template>
    <div>
        <b-row>
            <b-col md="3">
                <b-card>
                    <b-card-header>Friends</b-card-header>
                    <b-card-body>
                        <b-list-group>
                            <b-list-group-item>Ghaff</b-list-group-item>
                        </b-list-group>
                    </b-card-body>

                </b-card>
            </b-col>
            <b-col md="9">
                    <message-component></message-component>
            </b-col>
        </b-row>


    </div>
</template>
<script>
import MessageComponent from "./MessageComponent";
export default {
    components: {MessageComponent}
}
</script>
