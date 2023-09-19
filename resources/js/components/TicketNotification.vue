<template>
        <li class="nav-item dropdown nav-notifications">
            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i data-feather="bell"></i>
                <div class="indicator">
                    <div class="circle"> {{ tickets.length }} </div>
                </div>
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                    <p>{{ tickets.length }} New Notification<span v-if="tickets.length > 1">s</span></p>
                    <a class="text-muted" href="#" v-on:click="AllMarkAsRead()" v-if="tickets.length!=0"> Clear all</a>
                </div>
                <div class="p-1 overflow-y-scroll" style="max-height:280px">
                    <a href="#" class="dropdown-item d-flex align-items-center py-2" v-on:click="MarkAsRead(ticket)" v-for="(ticket, index) in tickets" :key="index">
                        <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                            <i class="icon-sm text-white" data-feather="user-plus"></i>
                        </div>
                        <div class="flex-grow-1 me-2">
                            <p style="white-space:normal; width:200px">{{ ticket.data.ticket.description}} </p>
                            <p class="tx-12 text-muted">{{ ticket.created_at | myOwnTime}}</p>
                        </div>
                    </a>
                    <a class="dropdown-item" href="#" v-if="tickets.length==0">No Notification</a>

                </div>
                <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                    <a class="" href="#" v-on:click="AllMarkAsRead()" v-if="tickets.length!=0">Mark All As Read </a>
                </div>
            </div>
        </li>



</template>

<script>
    export default {
    
        props: ['tickets'],

        methods:{

            MarkAsRead:function(ticket){
                var data = {
                    not_id : ticket.id,
                    ticket_id: ticket.data.ticket.id
                }
                //alert(data.ticket_id);
                console.log(data);
                axios.post('/ticket/notification/markAsRead', data).then(response => {
                    window.location.href = "/readTicket/notification/"+data.ticket_id;
                })
            },

            AllMarkAsRead:function(){
                axios.post('/ticket/notification/allMarkAsRead').then(response => {
                    window.location.href = "/notification/readAllTicket";
                })
            }

        }
    }
</script>