<template>
  <div class="chat-container">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeThisModal"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body scroll">
          <div class="list-group">
            <div v-if="!myBook_info.length">
              <label>You dont have any books uploaded</label>
              <label>Upload book first before sending request</label>
              <label>Click below To upload book</label><br />
              <button @click="uploadBookFormClickHandler">Upload Book</button>
            </div>

            <form v-if="myBook_info.length" @submit.prevent method="GET">
              <input type="hidden" name="book_id" value="id" />
              <input type="text" name="user_id" v-model="userIdToSend" /><br />
              <div class="img-card">
                <img :src="'/uploads/' + this.book_image" />
              </div>
              <Label>Book Title:</Label>
              
                  <p class="form-control">
                    {{book_name}}
                  </p>

 <Label>Author Name:</Label>
              
                  <p class="form-control">
                     {{author_name}}
                  </p>
                   <Label>Description:</Label>
              
                  <p class="form-control">
                    {{description}}

                  </p>
                
            <br>
              <label>choose your books</label>

              <div v-for="myBook in myBook_info" :key="myBook.books_id">
                
                <div class="card" >
         
           <div  style="display:flex">
             
             <input
                  style="width:10px"
                  type="checkbox"
                  v-model="checkBox"
                  :value="myBook.books_id"
                >
              
               
             <span >    {{'   '+ myBook.book_name }}</span>  
     
             <br> <br>
</div>
</div>
                
                
              </div>
              <button class="btn btn-primary" @click="sendSwapRequest">
                send swap request
              </button>
              {{ formData.req_book_title }}
              {{ formData.req_book_authors_name }}
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <button
            @click="closeThisModal"
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
          >
            Close
          </button>
          <button
            @click="sendSwapRequest"
            type="button"
            class="btn btn-primary"
          >
            Save changes
          </button>
        </div>
      </div>
    </div>

    <!-- <form @submit.prevent method="GET">
      <input type="hidden" name="book_id" value="id" />
      <input type="text" name="user_id" v-model="userIdToSend" /><br />

      <p>choose your books</p>

      <div v-for="myBook in myBook_info" :key="myBook.books_id">
        <input type="checkbox" v-model="checkBox" :value="myBook.books_id" />

        Title:{{ myBook.book_name }}
      </div>
      <button @click="sendSwapRequest">send swap request</button>
      {{ formData.req_book_title }}
      {{ formData.req_book_authors_name }}
    </form> -->
    <div v-if="uploadBookFormStatus">
      <UploadBookForm :email="email" @closeModal="uploadBookFormClickHandler" />
    </div>
  </div>
</template>

<script>
import UploadBookForm from "./UploadBookForm.vue";
export default {
  props: ["id","book_name","author_name","description", "userIdToSend", "email", "book_image"],
  components: { UploadBookForm },
  data() {
    return {
      uploadBookFormStatus: false,
      myBook_info: [],

      user_id: this.userIdToSend,
      book_id: this.id,
      checkBox: [],

      formData: {
        req_book_title: null,
        req_book_authors_name: null,
        req_book_description: null,
      },
    };
  },
  mounted() {
    console.log("swap book form component");
    console.log(this.book_image);
    console.log(this.email);
    axios.get("/myBooks").then((response) => {
      console.log(response.data);

      this.myBook_info = response.data;
    });
    console.log(this.email);
    console.log(this.userIdToSend);
    console.log(this.id);
  },
  methods: {
    uploadBookFormClickHandler() {
      this.uploadBookFormStatus = !this.uploadBookFormStatus;

      axios.post("api/myBooks", { email: this.email }).then((response) => {
        console.log(response.data);

        this.myBook_info = response.data;
      });
    },
    closeThisModal() {
      console.log("modal closed");
      this.$emit("closeSwapForm");
    },
    sendSwapRequest() {
      this.formData["send_to"] = this.userIdToSend;
      this.formData["book_id"] = this.id;
      this.formData["send_from"] = this.email;
      this.formData["checkBox"] = this.checkBox;
      console.log("check box is");
      console.log(this.checkBox);
      console.log(this.formData);
      console.log(this.formData);
      axios.post("/api/swap_books", this.formData).then((response) => {
        console.log(response);
        this.closeThisModal();
      });
    },
  },
};
</script>

<style scoped>

form{
  max-width: 420px;
  margin: 30px auto;
  background: white;
  text-align: left;
  padding: 40px;
  border-radius: 10px
}

label{
  color: black;
  margin: 30px auto;
  display: inline-block;
  margin: 25px 0 15px;
  font-size: 0.8em;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: bold;
 }
 /*
label {

  color: black;

  margin: 30px auto;
  display: inline-block;
  margin: 25px 0 15px;
  font-size: 0.9em;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: bold;
} */
.scroll {
  margin: 4px, 4px;
  padding: 4px;

  width: 100%;

  height: 70vh;
  overflow-x: hidden;
  overflow-y: auto;
  text-align: justify;
}
.card{
  height: 50px;
  padding:15px;
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