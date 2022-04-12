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
            @click="closeLoginModal"
            
            
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body scroll">
          <div class="list-group">

              <button><a href="/auth/google">Login with google</a></button>
            
          </div>
        </div>
        <div class="modal-footer">
          <button @click="closeLoginModal" type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  
  </div>
</template>

<script>
export default {
  methods:{
    closeLoginModal(){
      console.log('login closed')
      this.$emit('closeLogin')
    }
    
  }

}
</script>

<style scoped>
.scroll {
  margin: 4px, 4px;
  padding: 4px;

  width: 100%;

  height: 70vh;
  overflow-x: hidden;
  overflow-y: auto;
  text-align: justify;
}
.modal-dialog{
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