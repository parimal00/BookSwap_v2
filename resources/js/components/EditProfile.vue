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
              <Label>Name:</Label>
              <input
                class="form-control"
                
                name="bookTitle"
                placeholder="enter book title"
                v-model="profileData.name"
                required
              />
              <p style="color:red">
                {{errors.name}}
                </p><br />
              <Label>Latitude:</Label>
              <input
                type="text"
                class="form-control"
              
                v-model="profileData.latitude"
                placeholder="enter authors name"
                required
              /> <p style="color:red">
                {{errors.latitude}}
                </p><br />
              <Label>Longitude:</Label>
              <input
                type="text"
                class="form-control"
              
                v-model="profileData.longitude"
                placeholder="enter authors name"
                required
              />
               <p style="color:red">
                {{errors.longitude}}
                </p><br />
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button @click="editProfile" type="button" class="btn btn-primary">
              Save
            </button>
            axios.post('editProfile')
            function ProfileController@editProfile
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
         errors:{
        name:'',
        latitude:'',
        longitude:''
      },
      test:'',
      profileData: {
        name: "",
        latitude: "",
        longitude: "",
      },
    };
  },
  props: ["email", "longitude", "latitude", "name"],
  methods: {
    editProfile() {
      console.log(this.profileData);
        axios.post('editProfile',this.profileData).then((response)=>{
          console.log(response)
          console.log(response.status)
          if(response.status==200){
            this.$emit('editCompleted',this.profileData.name,this.profileData.latitude,this.profileData.longitude)
          }
        })
        .catch((errors)=>{
          console.log(errors.response.data) 
          this.errors.name = errors.response.data.name
                    this.errors.latitude = errors.response.data.latitude
                    this.errors.longitude = errors.response.data.longitude

        })
    },
  },
  mounted() {
    this.test=this.name
    this.profileData.name=this.name
    this.profileData.latitude=this.latitude
    this.profileData.longitude=this.longitude
    console.log(this.test)
    console.log(this.email);
    console.log(this.name);
    console.log(this.latitude);
    console.log(this.longitude);
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