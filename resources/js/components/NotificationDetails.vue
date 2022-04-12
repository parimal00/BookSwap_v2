<template>
  <div class="notificationDetailsController">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
          <button
            type="button"
            class="btn-close"
            @click="closeDialogModal(false)"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body scroll">
          <Label>My Books:</Label>

          <div v-for="(book, index) in books" :key="book.books_id">
            {{ book.book_ref_id }}
            <div class="card">
              <div class="img-card">
                <img :src="'/uploads/' + book.book_image" />
              </div>
              <br />
              <br />
              <button
                class="btn btn-primary form-control"
                @click="accept(book.book_ref_id)"
              >
                Accept this book
              </button>
            </div>

            <Label v-if="index != books.length - 1">OR</Label>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            @click="closeDialogModal(false)"
          >
            Close
          </button>
         
        </div>
      </div>
    </div>
    <div v-if="dialogModalStatus">
      <DialogModal :message="message" @closeDialogModal="closeDialogModal" />
    </div>
  </div>
</template>

<script>
import DialogModal from "./DialogModal";
export default {
  props: ["notification_id", "books", "book_id", "send_from"],
  components: { DialogModal },
  data() {
    return {
      message: "are you sure you want to accept the request?",
      radio: "",
      dialogModalStatus: false,
    };
  },
  methods: {
    closeDialogModal(status) {
      this.dialogModalStatus = false;
      console.log("bitch" + this.radio);

      if (status == true) {
        console.log(status);
        this.$emit("accepted", this.radio, true);
      } else if (status == false) {
        this.dialogModalStatus = false;
        this.$emit("accepted", this.radio, false);
      }
    },
    accept(book_ref_id) {
      this.radio = book_ref_id;
      this.dialogModalStatus = true;
    },
  },
  mounted() {
    console.log("notification details started");
    console.log(this.notification_id, this.books, this.book_id, this.send_from);
  },
};
</script>


<style scoped>
.opacity {
  opacity: 0.4;
}
.notificationDetailsController {
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

form {
  max-width: 420px;
  margin: 30px auto;
  background: white;
  text-align: left;
  padding: 40px;
  border-radius: 10px;
}

label {
  color: black;
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