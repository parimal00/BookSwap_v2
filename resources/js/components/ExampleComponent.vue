<template>
  <div>
    <div>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Navbar</a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item" @click="uploadBookClickHandlar">
                <a class="nav-link" href="#">Post a book?</a>
              </li>

              <li class="nav-item" @click="notificationClickHandlar">
                <a class="nav-link" href="#">Notifications</a>
              </li>
              <li class="nav-item" @click="messagesClickHandlar" >
                <a class="nav-link"  href="#">Chat</a>
              </li>
               <li class="nav-item" @click="profileClickHandlar" >
                <a class="nav-link"  href="#">Profile</a>
              </li>
            </ul>
            <div class="d-flex">
              <input
                class="form-control me-2"
             
                placeholder="Search"
                aria-label="Search"
                v-model="searchedData"
              />
              <!-- <button @click="search" class="btn btn-outline-success" type="submit">
                Search
              </button> -->
             
              </div>
               <button @click="search" class="btn btn-outline-success">click here</button>
              <button ><a href="logout">logout</a></button>
            
          </div>
        </div>
      </nav>

      <!-- <ul>
        <li
          @click="uploadBookClickHandlar"
          data-bs-toggle="modal"
          data-bs-target="#exampleModal"
        >
          Post a book?
        </li>
        <li
          data-bs-toggle="modal"
          data-bs-target="#exampleModal"
          @click="notificationClickHandlar"
        >
          Notifications
        </li>
        <li @click="messagesClickHandlar">Messages</li>
      </ul> -->
    </div>
    <div v-if="locationFetched && !searchStatus">
      <DisplayBooks :email="email" :latitude="latitude" :longitude="longitude" :searchedData="searchedData" :searchStatus="searchStatus"/>
      </div>
    

       <div v-if="locationFetched && searchStatus">
      <searchComponent :email="email" :latitude="latitude" :longitude="longitude" :searchedData="searchedData" :searchStatus="searchStatus" :key="componentKey"/>
      </div>
   

    <div v-if="messageStatus">
      <ChatComponent :email="email" @closeModal="messagesClickHandlar"/>
    </div>


    <div v-if="loginComponentStatus">
         <LoginComponent/>
    </div>
<div v-if="profileStatus" >
  <Profile @closeProfile="closeProfile"/>
</div>
 

    <div v-if="showUploadBookForm">
      <UploadBookForm :email="email" :latitude="latitude" :longitude="longitude" @closeModal="uploadBookClickHandlar" />
    </div>

    <div v-if="notificationStatus">
      <Notifications :email="email" @closeModal="notificationClickHandlar" />
    </div>
  </div>
</template>

<script>
import ChatTestComponent from './ChatTestComponent.vue'
import DisplayBooks from "./DisplayBooks.vue";
import UploadBookForm from "./UploadBookForm.vue";
import Notifications from "./Notifications.vue";
import Messages from "./Messages.vue";
import ChatComponent from "./ChatComponent.vue";
import LoginComponent from "./LoginComponent.vue";
import searchComponent from './searchComponent.vue';
import Profile from './Profile.vue'
export default {
  components: {Profile,ChatTestComponent,searchComponent,LoginComponent, DisplayBooks,ChatComponent,UploadBookForm, Notifications, Messages },
  props: ["email"],

   data() {
    return {
      latitude:null,
      longitude:null,
      locationFetched:false,
      showUploadBookForm: false,
      notificationStatus: false,
      messageStatus: false,
      loginComponentStatus:false,
      searchedData:null,
      searchStatus:false,
      componentKey:0,
      profileStatus:false
    };
  },
  methods: {
    closeProfile(){
      this.profileStatus = false;

    },
    profileClickHandlar(){
      this.profileStatus = true;
    },
    search(){
        this.searchStatus = true;
        this.componentKey=!this.componentKey;
       
    },
    uploadBookClickHandlar() {
      this.showUploadBookForm = !this.showUploadBookForm;
      console.log("waaaa");
    },
    notificationClickHandlar() {
      if(this.email == "no_email"){
        this.loginComponentStatus=!this.loginComponentStatus
      }
      else{
      this.notificationStatus = !this.notificationStatus;
      }
      console.log("clicked");
    },
    messagesClickHandlar() {
      if(this.email=="no_email"){
        this.loginComponentStatus=!this.loginComponentStatus
      }
      else{
      this.messageStatus = !this.messageStatus;
      }
    },
  },

  mounted() {
    console.log(this.email);
      if(!("geolocation" in navigator)) {
     alert('You must turn on your location')
     return;
    }
 
  else{
    
    navigator.geolocation.getCurrentPosition(pos => {
      this.latitude = pos.coords.latitude
      this.longitude = pos.coords.longitude
      console.log(this.latitude)
      console.log(this.longitude)
      console.log(this.email)
      this.locationFetched = true;
      axios.post('api/updateLocation',{
        longitude:this.longitude,
        latitude: this.latitude,
        email:this.email
      })
      .then((response)=>{
        console.log(response)
      })
      

    
    // }, err => {
    
     })
  }
  },
};
</script>
