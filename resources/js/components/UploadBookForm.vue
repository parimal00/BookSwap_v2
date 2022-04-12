<template>
 <div class="uploadbook-container"> 
   
 
    <div>
      
  
    
   
  <div class="modal-dialog modal-dialog-centered" >
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button @click="closeModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body scroll">
        <form @submit.prevent enctype="multipart/form-data">
          <Label>Email:</Label>
           <input class="form-control" v-model="form.bookTitle" name="bookTitle" placeholder="enter book title" required><br>
           <Label>Author:</Label>
           <input type="text" class="form-control" v-model ="form.bookAuthor" placeholder="enter authors name" required><br>
           <Label>Description:</Label>
           <textarea type="text " class="form-control" v-model ="form.description" placeholder="add description" required></textarea><br>
            choose image of your book <br>
    
           <input type="file" @change="uploadImage($event)" name="bookImage" >
            <button class="btn btn-primary" @click="submitUploadForm" type="submit">Upload Books</button>
        </form>   
      </div>
      <div class="modal-footer">
        <button type="button" @click="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" @click="submitUploadForm" class="btn btn-primary">Upload</button>
      </div>
    </div>
  </div>
</div>
<div v-if="showDialogModal">
  <DialogModal  :message="message" :bookTitle="form.bookTitle" :bookAuthor='form.bookAuthor' :bookImage='form.bookImage' :description="form.description" :email="email" :latitude="latitude" :longitude="longitude" @closeDialogModal = "toogleDialogModal" />
  </div>
   
     <!-- <form @submit.prevent enctype="multipart/form-data">
          
           <input v-model="form.bookTitle" name="bookTitle" placeholder="enter book title"><br>
           <input type="text" v-model ="form.bookAuthor" placeholder="enter authors name"><br>
           <input type="text " v-model ="form.description" placeholder="add description"><br>
            choose image of your book <br>
    
           <input type="file" @change="uploadImage($event)" name="bookImage" >
            <button @click="submitUploadForm" type="submit">Upload Books</button>
        </form>   -->
        
 </div>
</template>

<script>
import DialogModal from './DialogModal.vue'

export default {
    data(){
        
        return{
          message:"Are you sure you want to upload this book?",
          showDialogModal:false,
            
            form:{
                bookTitle:null,
                bookAuthor:null,
                description:null,
                bookImage:null
            }
        }
    },
    components:{DialogModal},
    mounted() {

            console.log(this.email)
        },
        props:['email','latitude','longitude'],
    methods:{
      closeDialogModal(){

      },
      toogleDialogModal(status){
        this.showDialogModal=!this.showDialogModal
         console.log(status)
         let fd= new FormData()
             fd.append('bookImage', this.form.bookImage)
             fd.append('bookTitle',this.form.bookTitle)
             fd.append('bookAuthor',this.form.bookAuthor)
             fd.append('description',this.form.description)
             fd.append('email',this.email)
        
             
             

             
            
          
            axios.post('/postBook',fd)
            .then((response)=>{
                console.log(response)

                this.closeModal()

            }).catch((error)=>{
              console.log(error)
            })
         

      },
       closeModal(){
         this.$emit("closeModal")
         console.log("modal closed")
       },

        uploadImage(event){
    //          let data = new FormData();
    // data.append('name', 'bookImage');
    // data.append('file', event.target.files[0]); 
    // this.form.bookImage = data
    //     //console.log(event.target.fil)

        this.form.bookImage = event.target.files[0];
              
              

        


        },
        submitUploadForm(){
          this.showDialogModal=true
            // let fd= new FormData()
            //  fd.append('bookImage', this.form.bookImage)
            //  fd.append('bookTitle',this.form.bookTitle)
            //  fd.append('bookAuthor',this.form.bookAuthor)
            //  fd.append('description',this.form.description)
            //  fd.append('email',this.email)
             

             
            
          
            // axios.post('/api/postBook',fd)
            // .then((response)=>{
            //     console.log(response)

            // })
        }
    }

}
</script>

<style scoped>
.uploadbook-container{
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
form{
  max-width: 420px;
  margin: 30px auto;
  background: white;
  text-align: left;
  padding: 40px;
  border-radius: 10px
}

label{
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
                margin:4px, 4px;
                padding:4px;
              
                width: 100%;
                height: 70vh;
                overflow-x: hidden;
                overflow-y: auto;
                text-align:justify;
            }
input{
  display: block;
  padding: 10px 6px;
  width: 100%;
  box-sizing: border-box;
  border: none;
  border-bottom: 1px solid #ddd;
  color: #555;
}

</style>