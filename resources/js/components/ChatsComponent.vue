<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Messages</div>

                    <div class="card-body p-0">
                       <ul class="list-unstyle" style="height:300px; overflow-y:scroll"  v-chat-scroll>
                        <li class="p-2" v-for="(message, index) in messages" :key="index">
                            <strong>{{message.user.name}}</strong>
                            {{message.message}}
                        </li>
                       </ul>
                    </div>

                    <input
                        @keydown="sendTypingEvent"
                        @keyup.enter = "sendMessage"
                        v-model="newMessage"
                        type="text"
                        name="message" 
                        placeholder="Enter your message ..." 
                        class="form-controll"/>
                    <span class="text-muted" v-if="activeUser">{{ activeUser.name}} User is typing ...</span>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header">Active User</div>
                    <div class="card-body">
                        <ul>
                            <li class="py-2" v-for="(user, index) in users" :key="index">{{user.name}}</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
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
