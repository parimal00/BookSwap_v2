<template>
  <div>
    <input type="checkbox" v-model="checkbox" value="one" />
    <input type="checkbox" v-model="checkbox" value="two" />
    <input type="checkbox" v-model="checkbox" value="three" />
    <input type="checkbox" v-model="checkbox" value="four" />
    <button @click="check">click here</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      book_info: [],
      name: null,
      checkbox: [],
    };
  },
  methods: {
    check() {
      console.log(this.checkbox);
      axios.post("api/check", { checkbox: this.checkbox }).then((response) => {
        console.log(response);
      });
    },
  },
  props: ["email"],
  mounted() {
    axios.post("api/myBooks", { email: this.email }).then((response) => {
      console.log(response);
      response.data.forEach((element) => {
        book_info.push(element);

//divide it into 2d array
      });
    });
    axios.post("api/profile", { email: this.email }).then((response) => {
      console.log(response);
    });
  },
};
</script>

<style>
</style>