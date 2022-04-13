<template>
  <div class="chat-container">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ friend_name }}</h5>
          <button
            @click="closethisModal"
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body scroll">
          <div class="list-group">
            <div v-for="(message, index) in chat.messages" :key="message.id">
              <div
                v-if="message.email == email"
                class="d-flex align-items-baseline mb-4"
              >
                <div>{{ message.name }}:</div>
                <div class="pe-2">
                  <div class="card-container">
                    <div
                      class="my_card card card-text d-inline-block p-2 px-3 m-1"
                    >
                      {{ message.message }}
                    </div>
                  </div>

                  <div>
                    <div class="small">01:10PM</div>
                  </div>
                </div>
              </div>

              <div
                v-if="message.email == sendMsgTo"
                class="d-flex align-items-baseline mb-4"
              >
                <div class="position-relative">
                  <div>{{ message.name }}:</div>
                </div>

                <div class="pe-2">
                  <div>
                    <div
                      class="
                        others_card
                        card card-text
                        d-inline-block
                        p-2
                        px-3
                        m-1
                      "
                    >
                      {{ message.message }}
                    </div>
                  </div>
                  <div>
                    <div class="small">01:10PM</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-container">
              <div style="visibility: hidden">
                <div class="my_card card card-text d-inline-block p-2 px-3 m-1">
                  <p>and this is good looking</p>
                </div>
              </div>
            </div>
            <div class="scroll_bottom"></div>

            <ul>
              <div></div>
            </ul>
          </div>
        </div>
        <div class="modal-footer">
          <div class="input-group">
            <input
              @keyup.enter="sendMessage"
              v-model="messages"
              type="text"
              class="form-control border-0"
              placeholder="Write a message..."
            />
            <div class="input-group-text bg-transparent border-0">
              <button class="btn btn-light text-secondary">
                <i class="fas fa-smile"></i>
              </button>
            </div>
            <!-- <div class="input-group-text bg-transparent border-0">
                        <button class="btn btn-light text-secondary">
                            <i class="fas fa-microphone"></i>
                        </button>
                    </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script >
export default {
  props: ["email", "sendMsgTo", "name"],
  watch: {
    chat(oldValue, newValue) {
      console.log("typing");
      //       var container = this.$el.querySelector(".modal-body");
      // container.scrollTo(0,container.scrollHeight+300)
    },
  },
  methods: {
    closethisModal() {
      this.$emit("closeModal");
    },

    sendMessage() {
      console.log(this.messages);

      let messages = {
        message: this.messages,
        email: this.email,
        name: this.name,
      };
      this.chat.messages.push(messages);
      var scroll_bottom = this.$el.querySelector(".scroll_bottom");

      var container = this.$el.querySelector(".modal-body");
      container.scrollTo(0, scroll_bottom.offsetTop);

      axios
        .post("api/sendMessages", {
          msg_to: this.sendMsgTo,
          msg_from: this.email,
          name: this.name,
          message: this.messages,
        })
        .then((response) => {
          this.messages = "";
          console.log(response);
        });
    },
  },
  mounted() {
    console.log("--------------------chattest message----------------");
    console.log(this.name);
  axios.get('api/getName/'+this.sendMsgTo)
  .then((response)=>{
    console.log(response)
    this.friend_name=response.data
  })

    axios
      .post("/api/getPusherPath", {
        email: this.email,
      })
      .then((response) => {
        console.log(response.data);

        response.data.forEach((element) => {
          console.log(element.path);
          Echo.channel("chat." + element.path).listen("ChatEvent", (e) => {
            console.log(e);
            console.log("waaasupp");
            var scroll_bottom = this.$el.querySelector(".scroll_bottom");

            var container = this.$el.querySelector(".modal-body");
            console.log(container);
            container.scrollTo(0, scroll_bottom.offsetTop);

            this.chat.messages.push({
              message: e.message,
              email: this.sendMsgTo,
              name: e.name,
            });

            console.log(
              "*********************chattestcomonent ended**********************"
            );
          });
        });
      });

    console.log("send msg to" + this.sendMsgTo);
    axios
      .post("api/getMessages", {
        email: this.email,
        sendMsgTo: this.sendMsgTo,
      })
      .then((response) => {
     
        console.log(response.data);
        response.data.forEach((element) => {
               
        
       
          console.log(element);
          this.chat.messages.push({
            message: element.message,
            email: element.msg_from,
            name: element.name,
          });


          //to scroll to botoom
        });
              var scroll = this.$el.querySelector(".scroll");
              console.log(scroll)
              scroll.scrollTo(0,100)


       
      });

       
  },
  data() {
    return {
      friend_name:'',
      messages: null,
      chat: {
        messages: [],
      },
    };
  },
};
</script>


<style scoped>
.my_card {
  background-color: yellow;
}
.others_card {
  background-color: greenyellow;
}
.card-container {
  max-width: 250px;
  overflow: auto;
}
.card {
  overflow: auto;
}
.card-text {
  max-width: 80%;
}
a.nav-link {
  color: gray;
  font-size: 18px;
  padding: 0;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 2px solid #e84118;
  padding: 2px;
  flex: none;
}

input:focus {
  outline: 0px !important;
  box-shadow: none !important;
}

.card-text {
  border: 2px solid #ddd;
  border-radius: 8px;
}

.scroll {
  margin: 4px, 4px;
  padding: 4px;

  width: 100%;
  height: 70vh;
  /* height: 70vh; */
  overflow-x: hidden;
  overflow-y: auto;
  text-align: justify;
}
.modal-dialog {
  width: 80%;
}

.chat-container {
  position: fixed;
  top: 5px;

  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>