<template>
  <div class="chat-container">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
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
            <ul>
              <div>
                <ul>
                  <li
                    class="list-group-item list-group-item-action"
                    v-for="messages in chat.messages"
                    :key="messages.msg_id"
                  >
                    {{ messages }}
                  </li>
                </ul>
                <input v-model="messages" placeholder="type your message here...." @keyup.enter="sendMessage" />
              </div>
            </ul>
          </div>
        </div>
        <div class="modal-footer">
          <button
            @click="closethisModal"
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
          >
            Close
          </button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- <div>
    <p>hello chatMessage {{ email }}</p>
    <ul>
      <li v-for="messages in chat.messages" :key="messages">
        {{ messages }}
      </li>
    </ul>
    <input v-model="messages" @keyup.enter="sendMessage" />
  </div> -->
</template>

<script >
export default {
  props: ["email", "sendMsgTo"],
  methods: {
    closethisModal() {
      this.$emit("closeModal");
    },
    sendMessage() {
      console.log(this.messages);
      this.chat.messages.push(this.messages);

      axios
        .post("api/sendMessages", {
          msg_to: this.sendMsgTo,
          msg_from: this.email,
          message: this.messages,
        })
        .then((response) => {
          this.messages = "";
          console.log(response);
        });
    },
  },
  mounted() {
    axios
      .post("/api/getPusherPath", {
        email: this.email,
      })
      .then((response) => {
        console.log(response.data);
        response.data.forEach((element) => {
          console.log(element.path);
          Echo.channel("chat." + element.path).listen("ChatEvent", (e) => {
            console.log(e.message);
            this.chat.messages.push(e.message);
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
          console.log(element.message);
          this.chat.messages.push(element.message);
        });
      });
  },
  data() {
    return {
      messages: null,
      chat: {
        messages: [],
      },
    };
  },
};
</script>

<style scoped>
.scroll {
  margin: 4px, 4px;
  padding: 4px;

  width: 100%;

  height: 70vh;
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