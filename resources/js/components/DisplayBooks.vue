<template>
  <div>
    <section class="wrapper">
      <div class="container-fostrap">
        <div class="content">
          <div class="container">
            <div class="row my-4" v-for="(e, row) in books.length" :key="row">
              <div
                v-for="(f, column) in books[row].length"
                :key="column"
                class="col-xs-12 col-sm-4"
              >
                
                <div
                  class="card"
                  @click="
                    showSwapBookForm(
                      books[row][column].books_id,
                      books[row][column].author_name,
                      books[row][column].description,
                      books[row][column].email,
                      books[row][column].book_image,
                      books[row][column].book_name,
                  
                    )
                  "
                >
                  <div class="img-card">
                    <img :src="'/uploads/' + books[row][column].book_image" />
                  </div>
                  <div class="card-content">
                    <h4 class="card-title">
                      {{ books[row][column].book_name }}
                    </h4>
                    <p class="">Author:{{ books[row][column].author_name }}</p>
                    <p class="">Posted By:{{ books[row][column].name }}</p>
                    <p>{{ books[row][column].distance.toFixed(2) }} Kilometer</p>
                  </div>
                  <div class="card-read-more">
                    <div class="btn btn-primary btn-block">Swap Book</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <!-- <div style="background-color:green;position:fixed; top:85vh;left:10%;">
    <span class="badge bg-success">Request sent successfully!!!!!</span>
  </div> -->
    <!-- <div class="thisPage">
      <div v-for="book in books" :key="book.id">
        {{ book.image_path }}
        <img
          :src="'/uploads/' + book.book_image"
          width="100px"
          height="100px"
        />
        email = {{ book.email }} id = {{ book.id }} author =
        {{ book.author_name }} description = {{ book.description }}
        <button
          @click="
            showSwapBookForm(
              book.id,
              book.author_name,
              book.description,
              book.email
            )
          "
        >
          Swap Book
        </button>
      </div>
      <div v-if="show">
        <SwapBookForm :id="id" :userIdToSend="userIdToSend" :email="email" />
      </div>
    </div> -->
  
    <div v-if="show">
      <SwapBookForm
        :id="id"
        :userIdToSend="userIdToSend"
        :email="email"
        :book_image="book_image"
        :author_name="author"
        :book_name="book_name"
        :description="description"
        @closeSwapForm="closeSwapForm"
      />
    </div>
    <div v-if="loginComponentStatus">
      <LoginComponent @closeLogin="closeLogin" />
    </div>

    <infinite-loading @distance="1" @infinite="loadMoreData"></infinite-loading>
  </div>
</template>

<script>
import SwapBookForm from "./SwapBookForm.vue";
import LoginComponent from "./LoginComponent.vue";

export default {
  components: { LoginComponent, SwapBookForm },
  props: ["email", "latitude", "longitude", "searchedData", "searchStatus"],
  data() {
    return {
      count:0,
      locationFetched: false,
      loginComponentStatus: false,
      row: 0,
      page: 1,
      column: 0,
      no_of_row: 0,
      show: false,
      books: [],
      id: null,
      author: null,
      description: null,
      userIdToSend: null,
      allDataFetched: 0,
      book_image: "",
      book_name:''
    };
  },
  methods: {
    loadMoreData($state) {
      console.log(this.page)
      if (this.searchStatus == false) {
        if (this.allDataFetched == 0) {
          axios
            .post("/api/viewBooks?page=" + this.page, {
              latitude: this.latitude,
              longitude: this.longitude,
            })
            .then((response) => {
              this.count=this.count+1
              console.log(this.count)
              console.log(response);
              console.log(this.page)

              if (this.page > response.data.last_page) {
                this.allDataFetched = 1;
              }

              //this.no_of_row = parseInt(response.data.data.length / 3) + 1;

              const arr = response.data.data;
              while (arr.length) this.books.push(arr.splice(0, 3));

              $state.loaded();

              // const newArr = [];
              // while (arr.length) newArr.push(arr.splice(0, 3));

              // this.books.push(newArr);
              // console.log(this.books)
            });
        }

        this.page = this.page + 1;
      }
    },
    closeLogin() {
      this.loginComponentStatus = !this.loginComponentStatus;
    },

    closeSwapForm() {
      this.show = !this.show;
    },
    showSwapBookForm(id, author, description, user_id, book_image,book_name) {
      if (this.email == "no_email") {
        this.loginComponentStatus = !this.loginComponentStatus;
      } else {
        this.show = true;
        this.id = id;
        this.author = author;
        this.description = description;
        this.userIdToSend = user_id;
        this.book_image = book_image;
        this.book_name=book_name
        console.log(this.books.email);
      }
    },
  },
  created() {
    //do we support geolocation
    //   if(!("geolocation" in navigator)) {
    //   }
    // else{
    //   navigator.geolocation.getCurrentPosition(pos => {
    //     this.latitude = pos.coords.latitude
    //     this.longitude = pos.coords.longitude
    //     console.log(this.latitude)
    //     console.log(this.longitude)
    //     console.log(this.email)
    //     this.locationFetched = true
    //   }, err => {
    //   })
    // }
  },
  mounted() {
  //   axios.get("/api/viewBooks?page=" + 1).then((response) => {
  //     console.log(response);
  //    console.log(response.data.last_page)
  //     const arr = response.data.data;
  //     while (arr.length) this.books.push(arr.splice(0, 3));
  //     console.log(this.books);
  //   });
   },
};
</script>

<style>
@import url(https://fonts.googleapis.com/css?family=Roboto:400,100,900);

html,
body {
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  height: 100%;
  width: 100%;
  background: #fff;
  font-family: "Roboto", sans-serif;
  font-weight: 400;
}

.wrapper {
  display: table;
  height: 100%;
  width: 100%;
}

.container-fostrap {
  display: table-cell;
  padding: 1em;
  text-align: center;
  vertical-align: middle;
}
.fostrap-logo {
  width: 100px;
  margin-bottom: 15px;
}
h1.heading {
  color: #fff;
  font-size: 1.15em;
  font-weight: 900;
  margin: 0 0 0.5em;
  color: #505050;
}
@media (min-width: 450px) {
  h1.heading {
    font-size: 3.55em;
  }
}
@media (min-width: 760px) {
  h1.heading {
    font-size: 3.05em;
  }
}
@media (min-width: 900px) {
  h1.heading {
    font-size: 3.25em;
    margin: 0 0 0.3em;
  }
}
.card {
  display: block;
  margin-bottom: 20px;
  line-height: 1.42857143;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  transition: box-shadow 0.25s;
}
.card:hover {
  box-shadow: 0 8px 17px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
.img-card {
  width: 100%;
  height: 200px;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
  display: block;
  overflow: hidden;
}
.img-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: all 0.25s ease;
}
.card-content {
  padding: 15px;
  text-align: left;
}
.card-title {
  margin-top: 0px;
  font-weight: 700;
  font-size: 1.65em;
}
.card-title a {
  color: #000;
  text-decoration: none !important;
}
.card-read-more {
  border-top: 1px solid #d4d4d4;
}
.card-read-more a {
  text-decoration: none !important;
  padding: 10px;
  font-weight: 600;
  text-transform: uppercase;
}
.thisPage {
  width: 100%;
  height: 100vh;
  background-color: red;
}
</style>