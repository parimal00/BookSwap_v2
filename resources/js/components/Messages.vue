<template>
  <div>
      <div v-for="message in messages" :key="message.msg_id">
                
                <div @click="showMessagesClickHandlar(message.msg_from)">
            {{message.name}}
            </div>
            {{message.message}}<br>
            


      </div>
      <input placeholder="waaaaaa">

      
  </div>
</template>

    <script>
    export default {
        data(){
            return{
                messages:'',
                showMessages:true
                


            }
        },
        methods:{

        showMessagesClickHandlar(from){
            //call api to get messages from selected user
            console.log(from)

        }
        },
        mounted(){
            axios.get('api/messages')
            .then((response)=>{
                this.messages= response.data
                console.log(response.data)

            })
        
        }

    }

    </script>

    <style>

    </style>