<template>
  <div class="uploadbook-container">
    <div>
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body scroll">
            <form @submit.prevent enctype="multipart/form-data">
              <div class="img-card">
                <img :src="'/uploads/' + this.book_image" />
              </div>
              <Label>Book Title:</Label>
              <input
                class="form-control"
                v-model="form.book_name"
                placeholder="enter book title"
                required
              />
              <p style="color: red"></p>
              <br />
              <Label>Author Name:</Label>
              <input
                class="form-control"
                name="bookTitle"
                v-model="form.author_name"
                placeholder="enter book title"
                required
              />
              <p style="color: red"></p>
              <Label>Description:</Label>
              <input
                class="form-control"
                name="bookTitle"
                v-model="form.description"
                placeholder="enter book title"
                required
              />
              <p style="color: red"></p>
            </form>
           
          </div>
          <div class="modal-footer">
                <div v-if="unauthorizedStatus">
                <span class="badge bg-danger"> {{ errors.unauthorized }}</span>
              </div>
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>

            <button type="button" @click="update" class="btn btn-primary">Save</button>
            axios.post('update') function ProfileController@updateBook
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["book_id", "book_name", "book_image", "author_name", "description"],
  data() {
    return {
        unauthorizedStatus:false,
        errors:{
            unauthorized:''
        },
      form: {
        book_name: "",
        author_name: "",
        description: "",
      },
    };
  },
  methods: {
    update() {
        console.log('update pressed')
      
      axios.post("book/" + this.book_id, this.form).then((response) => {
        console.log(response);
        if(response.status==200){
            this.$emit('closeEditBook',this.form.book_name)
        }

      })
      .catch((errors)=>{
        console.log(errors)
        if(errors.response.status==401){
            this.errors.unauthorized=errors.response.data
            this.unauthorizedStatus=true
        }
      })
    },
  },

  mounted() {
    console.log("edit book mounted");
    console.log(
      this.book_id,
      this.author_name,
      this.description,
      this.book_image
    );
    this.form.book_name = this.book_name;
    this.form.author_name = this.author_name;
    this.form.description = this.description;
  },
};
</script>


<style scoped>
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
form {
  max-width: 420px;
  margin: 30px auto;
  background: white;
  text-align: left;
  padding: 40px;
  border-radius: 10px;
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