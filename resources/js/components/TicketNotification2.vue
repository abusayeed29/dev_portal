<template>

        <li class="nav-item dropdown">
            
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Notifications <span class="badge badge-light text-danger" id="count-notification">{{ tickets.length }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#" v-on:click="MarkAsRead(ticket)" v-for="ticket in tickets">
                    {{ ticket.data.ticket.description }} {{ ticket.created_at | myOwnTime}}
                </a>
                <div role="separator" class="dropdown-divider" v-if="tickets.length!=0"></div>
                <a class="dropdown-item" href="#" v-on:click="AllMarkAsRead()" v-if="tickets.length!=0">
                    Read All Mark As Read 
                </a>
                <a class="dropdown-item" href="#" v-if="tickets.length==0">No Notification</a>
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
                axios.post('/ticket/notification/markAsRead', data).then(response => {
                    window.location.href = "/ticket/notification/"+data.ticket_id;
                })
            },

            AllMarkAsRead:function(){
                axios.post('/notification/allMarkAsRead').then(response => {
                    window.location.href = "/notification/readAllTicket";
                })
            }

        }
    }
</script>