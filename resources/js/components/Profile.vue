<template>
  <div>
    <div class="profileContainer">
      <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
              @click="closeProfile"
            ></button>
          </div>
          <div class="modal-body scroll">
            <form @submit.prevent enctype="multipart/form-data">
              <Label>Name:</Label>
              <p class="form-control">{{ name }}</p>
              <Label>Email:</Label>
              <p class="form-control">{{ email }}</p>
              <Label>Latitude:</Label>
              <p class="form-control">{{ latitude }}</p>
              <Label>Longitude:</Label>
              <p class="form-control">{{ longitude }}</p>
             
              <button class="btn btn-primary form-control" @click="editProfile">
                Edit Profile
              </button>

              <br />

              <Label>My Books:</Label>
              
              <div v-if="myBooks.books_id">
              <div  v-for="(books, index) in myBooks" :key="books.books_id">
                {{ index }}
                <div class="card" :class="{ opacity: books.display === 0 }">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-trash"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                    />
                    <path
                      fill-rule="evenodd"
                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                    />
                  </svg>

                  <button
                    type="button"
                    class="btn-close btn-danger"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    @click="closeProfile"
                  ></button>
                  <div class="img-card">
                    <img :src="'/uploads/' + books.book_image" />
                  </div>

                  <Label v-if="books.display == 0">Book Sent for request</Label>
                  <br />
                  <Label
                    ><div>{{ books.book_name }}</div></Label
                  >
                </div>
                <button
                  class="btn btn-primary"
                  @click="
                    editBook(
                      index,
                      books.books_id,
                      books.book_name,
                      books.author_name,
                      books.description,
                      books.book_image
                    )
                  "
                >
                  Edit
                </button>
                <button class="btn btn-primary" @click="deleteBook(books.books_id)">Delete</button>
                <br />
                <br />
                <br />
                <br />
              </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
             <div v-if="unauthorizedDeleteStatus">
                <span class="badge bg-danger"> {{ errors.unauthorizedDelete }}</span>
              </div>
             <div v-if="unavailableStatus">
                <span class="badge bg-danger"> {{ errors.unavailable }}</span>
              </div>
             <div v-if="editSuccessStatus">
                <span class="badge bg-success"> {{ editSucess }}</span>
              </div>
            <div v-if="editBookSuccessStatus">
              <span class="badge bg-success"> {{ success.editBook }}</span>
            </div>
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              @click="closeProfile"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="editProfileStatus">
      <EditProfile
        :email="email"
        :name="name"
        :longitude="longitude"
        :latitude="latitude"
        @editCompleted="editCompleted"
      />
    </div>
    <div v-if="editBookStatus">
      <EditBook
        :book_id="book.book_id"
        :book_name="book.book_name"
        :book_image="book.book_image"
        :author_name="book.author_name"
        :description="book.description"
        @closeEditBook="closeEditBook"
      />
    </div>
     <div v-if="dialogModalStatus">
      <DialogModal
       :message="dialogMessage"
       @closeDialogModal="closeDialogModal"
      />
    </div>
  </div>
</template>

<script>
import EditProfile from "./EditProfile.vue";
import EditBook from "./EditBook.vue";
import DialogModal from './DialogModal.vue'
export default {
  components: { EditBook, EditProfile ,DialogModal},
  data() {
    return {
      errors:{
        unavailable:'',
        unauthorizedDelete:''
      },
      unauthorizedDeleteStatus:false,
      unavailableStatus:false,
      deleteBookId:'',
      dialogModalStatus:false,
      dialogMessage:"Are you sure you want to delete this book?",
      index: "",
      success: {
        editBook: "Book Edited Successfully",
      },
      book: {
        book_id: "",
        book_name: "",
        book_image: "",
        author_name: "",
        description: "",
      },
      editBookStatus: false,
      editSuccessStatus: false,
      editBookSuccessStatus: false,
      editSucess: "Profile Edited Successfully",
      email: "",
      name: "",
      latitude: "",
      longitude: "",
      opacityStatus: false,
      myBooks: [],
      editProfileStatus: false,
    };
  },
  mounted() {
    this.getProfile();
  },
  methods: {
    closeDialogModal(status){
      this.dialogModalStatus=false
      console.log(status)

      if(status==true){
        axios.delete('/books/'+this.deleteBookId)
        .then((response)=>{
          console.log(response)
          
        }).catch((error)=>{
          console.log(error)
          if(error.response.status==401){
            this.unauthorizedDeleteStatus=true
            this.errors.unauthorizedDelete = error.response.data
          }
          if(error.response.status==403){
            this.errors.unavailable=error.response.data;
            this.unavailableStatus=true

          }
        })
      }

    },
    deleteBook(book_id){
      this.deleteBookId=book_id
      this.dialogModalStatus=true
      console.log(book_id)
    },
    closeEditBook(book_name) {
      this.myBooks[this.index].book_name = book_name;

      this.editBookStatus = false;
      this.editBookSuccessStatus = true;

      setTimeout(() => {
        this.editBookSuccessStatus = false;
      }, 3000);
    },
    editBook(index, books_id, book_name, author_name, description, book_image) {
      console.log(index, books_id, author_name, description, book_image);
      this.book.book_id = books_id;
      this.book.author_name = author_name;
      this.book.description = description;
      this.index = index;

      this.book.book_image = book_image;
      this.book.book_name = book_name;
      console.log(
        this.book.book_id,
        this.book.author_name,
        this.book.description,
        this.book.book_image
      );
      this.editBookStatus = true;
    },
    editCompleted(name, latitude, longitude) {
      console.log("teset");
      console.log(name);
      console.log(latitude);
      console.log(longitude);
      this.name = name;
      this.latitude = latitude;
      this.longitude = longitude;
      this.editSuccessStatus = true;
      setTimeout(() => {
        this.editSuccessStatus = false;
      }, 3000);
      this.editProfileStatus = false;
      // this.editProfileStatus = false,100;
      // this.editSucess= "Edit successful";
    },
    editProfile() {
      this.editProfileStatus = true;
    },
    closeProfile() {
      console.log("modal closed");
      this.$emit("closeProfile");
    },
    getProfile() {
      axios.get("profile").then((response) => {
        console.log(response);
        this.email = response.data[0].email;
        this.name = response.data[0].name;
        this.latitude = response.data[0].latitude;
        this.longitude = response.data[0].longitude;
        console.log(this.email);
        console.log(response.data[0].latitude);
        console.log(this.latitude);

        

        this.myBooks = response.data;
      });
    },
  },
};
</script>

<style>
.opacity {
  opacity: 0.4;
}
.profileContainer {
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