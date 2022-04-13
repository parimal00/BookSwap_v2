<template>
  <div class="uploadbook-container">
    <div class="abcd">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Notifications</h5>
            <button
              type="button"
              @click="closeModal"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body scroll">
            <div class="list-group">
              <div v-if="!notifications.length">
                There is no new Notifications for you!!
              </div>

              <div
                class="list-group-item list-group-item-action"
                v-for="notification in notifications"
                :key="notification.notification_id"
              >
                <div v-if="notification.status === 2">
                  <div v-if="notification.books.length == 1">
                    <div v-if="notification.send_to == email">
                      {{ notification.send_from }} wants to swap your book
                      <div
                        v-for="myBooks in notification.myBooks"
                        :key="myBooks.books_id"
                      >
                        "{{ myBooks.book_name }}"{{ myBooks.books_id }}
                      </div>
                      with
                      <div v-for="book in notification.books" :key="book.id">
                        ({{ book.book_name }}){{ book.books_id }}

                        <Button
                          class="btn btn-primary"
                          @click="
                            acceptSingleRequest(
                              notification.send_from,
                              notification.notification_id,
                              notification.books,
                              notification.book_id
                            )
                          "
                        >
                          Accept
                        </Button>
                        <Button
                          class="btn btn-primary"
                          @click="
                            rejectReqHandlar(
                              notification.send_from,
                              notification.notification_id,
                              notification.books
                            )
                          "
                        >
                          Reject
                        </Button>
                        <Button
                        class="btn btn-primary"
                        @click="
                          openNotificationDetails(
                            notification.books,
                            notification.send_from,
                            notification.notification_id,
                            notification.book_id
                          )
                        "
                      
                     
                      >
                        View Details
                      </Button>
                      </div>
                    </div>
                    <div v-if="notification.send_from == email">
                      Request sent to {{ notification.send_to }}
                    </div>
                  </div>
                  <div v-if="notification.books.length > 1">
                    <div v-if="notification.send_to == email">
                      {{ notification.send_from }} wants to swap your book

                      <div
                        v-for="myBooks in notification.myBooks"
                        :key="myBooks.books_id"
                      >
                        "{{ myBooks.book_name }}"
                      </div>

                      with

                      <fieldset id="group1">
                        <div v-for="book in notification.books" :key="book.id">
                          ({{ book.book_name }})
                          <input
                            type="radio"
                            :value="book.book_ref_id"
                            v-model="radio"
                          />
                          or
                        </div>
                      </fieldset>

                      <Button
                        class="btn btn-primary"
                        @click="
                          acceptReqHandlar(
                            notification.send_from,
                            notification.notification_id,
                            notification.books,
                            notification.book_id
                          )
                        "
                      >
                        Accept
                      </Button>
                      <Button
                        class="btn btn-primary"
                        @click="
                          rejectReqHandlar(
                            notification.send_from,
                            notification.notification_id,
                            notification.books
                          )
                        "
                      >
                        Reject
                      </Button>
                      <Button
                        class="btn btn-primary"
                        @click="
                          openNotificationDetails(
                            notification.books,
                            notification.send_from,
                            notification.notification_id,
                            notification.book_id
                          )
                        "
                      
                     
                      >
                        View Details
                      </Button>
                    </div>
                    <div v-if="notification.send_from == email">
                      Request sent to {{ notification.send_to }}
                    </div>
                  </div>
                </div>
                <div v-if="notification.status === 1">
                  <div @click="chat(notification.send_from)"  v-if="notification.send_to == email">
                    {{ notification.send_from }} has accepted your request with
                    <div
                      v-for="acc_book in notification.acc_book"
                      :key="acc_book.books_id"
                    >
                      {{ acc_book.book_name }}
                    </div>
                  </div>
                  <div @click="chat(notification.send_to)" v-if="notification.send_from == email">
                    You have accepted the request from
                    {{ notification.send_to }}
                  </div>
                </div>
                <div v-if="notification.status === 0">
                  <div v-if="notification.send_to == email">
                    <p>
                      {{ notification.send_from }} has rejected your request
                    </p>
                  </div>
                  <div v-if="notification.send_from == email">
                    <p>
                      You have rejected the request from
                      {{ notification.send_from }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="notificationDetailsStatus">
      <notification-details
        :notification_id="notificationDetails.notification_id"
        :books="notificationDetails.books"
        :book_id="notificationDetails.book_id"
        :send_from="notificationDetails.send_from"

         @accepted="acceptReq"
      />
    </div>
  </div>
</template>

<script>
import NotificationDetails from "./NotificationDetails.vue";

export default {
  components: { NotificationDetails },
  data() {
    return {
      notificationDetails: {
        books: [],
        send_from: "",
        notification_id: "",
        book_id: "",
        radio: "",
      },
      notificationDetailsStatus: false,
      sender_book_id: 0,
      books_idz: [],
      radio: null,
      notifications: [],

      formData: {
        email: this.email,
      },
    };
  },

  props: ["email"],
  methods: {
    chat(user){
      console.log(user)
    },
        acceptReq(radio,status) {

          if(status!=true){
                this.notificationDetailsStatus=false
                return
          }

          this.notificationDetailsStatus=false
          console.log("the radio is "+radio)
        
         
      this.notificationDetails.books.forEach((element) => {
        this.books_idz.push(element.books_id);
      });

      this.books_idz.forEach((element) => {
        console.log(element);
      });
     this.radio=radio

    
      console.log(this.radio);

      axios
        .post("/api/acceptReq", {
          notification_id: this.notificationDetails.notification_id,
          books: this.books_idz,
          radio: this.radio,
          send_from: this.email,
          send_to: this.notificationDetails.send_from,
          book_id: this.notificationDetails.book_id,
        })
        .then((response) => {
          console.log(response);
          this.loadNotifComponent();
        });
    },
    openNotificationDetails(books, send_from, notification_id, book_id) {
      console.log(book_id);

      this.notificationDetails.send_from = send_from;
      this.notificationDetails.notification_id = notification_id;
      this.notificationDetails.books = books;
      this.notificationDetails.book_id = book_id;
      this.notificationDetailsStatus = true;
    },
    acceptSingleRequest(send_from, notification_id, books, book_id) {
      console.log(send_from);
      console.log(notification_id);
      console.log(books);
      console.log(book_id);
      books.forEach((element) => {
        this.sender_book_id = element.books_id;
      });
      console.log(this.sender_book_id);
      axios
        .post("/api/acceptSingleReq", {
          notification_id: notification_id,
          send_from: this.email,
          send_to: send_from,
          book_id: book_id,
          sender_book_id: this.sender_book_id,
        })
        .then((response) => {
          console.log(response);
          this.loadNotifComponent();
        });
    },
    loadNotifComponent() {
      axios.post("/api/view_notification", this.formData).then((response) => {
        this.notifications = response.data;
        console.log(this.notifications);
        console.log(response);
      });
    },

    closeModal() {
      console.log("clicked");
      this.$emit("closeModal");
    },

    acceptReqHandlar(send_from, notification_id, books, book_id) {
      books.forEach((element) => {
        this.books_idz.push(element.books_id);
      });

      this.books_idz.forEach((element) => {
        console.log(element);
      });
      console.log(books);

      console.log(send_from);
      console.log(notification_id);
      console.log(book_id);
      console.log(this.radio);

      axios
        .post("/acceptReq", {
          notification_id: notification_id,
          books: this.books_idz,
          radio: this.radio,
          send_from: this.email,
          send_to: send_from,
          book_id: book_id,
        })
        .then((response) => {
          console.log(response);
          this.loadNotifComponent();
        });
    },
    rejectReqHandlar(send_from, notification_id, books) {
      console.log("rejected");

      books.forEach((element) => {
        this.books_idz.push(element.books_id);
      });

      axios
        .post("/api/rejectReq", {
          notification_id: notification_id,
          books: this.books_idz,
          send_to: send_from,
          send_from: this.email,
        })
        .then((response) => {
          console.log(response);
          this.loadNotifComponent();
        });
    },
  },
  mounted() {
    console.log("email is " + this.email);
    axios.post("/api/view_notification", this.formData).then((response) => {
      this.notifications = response.data;
      console.log(this.notifications);
      console.log(response);
    });
  },
};
</script>

<style scoped>
.modal-body {
  width: 100%;
}
.uploadbook-container {
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

label {
  color: #aaa;
  margin: 30px auto;
  display: inline-block;
  margin: 25px 0 15px;
  font-size: 0.6em;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: bold;
}
.scroll {
  margin: 4px, 4px;
  padding: 4px;

  width: 100%;

  height: 70vh;
  overflow-x: hidden;
  overflow-y: auto;
  text-align: justify;
}
input {
  display: block;
  padding: 10px 6px;
  width: 100%;
  box-sizing: border-box;
  border: none;
  border-bottom: 1px solid #ddd;
  color: #555;
}
</style>