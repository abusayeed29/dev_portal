<template>
    <div class="active-user">
        <h6 class="auser-title mb-1">Active User</h6>
        <ul>
            <li class="py-2" v-for="(user, index) in users" :key="index">{{user.name}}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props:['user'],
        data(){
            return {
                messages: [],
                newMessage: '',
                users:[],
                activeUser: false,
                typingTimer : false,
            }
        },

        created() {
            this.fetchMessages();
            
            Echo.join('chat')
                .here(user =>{
                    console.log('Here');
                    this.users = user;
                })
                .joining(user =>{
                    console.log(user);
                    this.users.push(user);
                })
                .leaving(user =>{
                    console.log('leaving');
                    this.users = this.users.filter( u =>u.id != user.id);
                })
                .listen('MessageSentEvent', (event) => {
                    this.messages.push(event.message);
                })
                .listenForWhisper('typing', user => {

                    this.activeUser = user;

                    if(this.typingTimer){
                        clearTimeout(this.typingTimer);
                    }

                    this.typingTimer = setTimeout(() => {
                        this.activeUser = false;
                    }, 3000)

                })
        },

        methods:{
            fetchMessages() {
                axios.get('/messages').then(response =>{
                    this.messages = response.data;
                })
            },

            sendMessage(){

                this.messages.push({
                    user: this.user,
                    message:this.newMessage
                });
                axios.post('messages', {message: this.newMessage});
                this.newMessage = '';

            },

            sendTypingEvent(){
                Echo.join('chat')
                    .whisper('typing', this.user);
            }
        }
    }
</script>
