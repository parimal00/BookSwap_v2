<template>
<div class="UploadBookPage">
    
    <form action="postBook" method="post" enctype="multipart/form-data">
          
           <input name="bookTitle" placeholder="enter book title"><br>
           <input type="text" name = "bookAuthor" placeholder="enter authors name"><br>
           <input type="text " name = "description" placeholder="add description"><br>
            choose image of your book <br>
    
           <input type="file" name="bookImage" >
            <button type="submit">Upload Books</button>
        </form>  
        </div>
</template>

<script>
export default {

}
</script>

<style>
    .UploadBookPage{
        width: 40vh;
        height:40vh;
        background-color: green;
        margin: auto;
        position: relative;
        top: 40

    }

</style>