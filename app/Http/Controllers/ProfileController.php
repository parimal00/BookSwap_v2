<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    function deleteBook($book_id){
           
           $book= DB::table('books')
            ->where('books_id',$book_id)
            ->first();

            $email = Auth::user()->email;
    
            if($book->email!=$email){
                return response('you are not authorized to delete this book',401);
            }

            if($book->display==0){
                return response('can only delete this book once you accept,reject or cancel the request',403);
            }
            DB::table('books')
            ->where('books_id',$book_id)
            ->delete();

            return response('book deleted',200);

    }
function updateBook($id,Request $request){
    
    
    $book_id=$id;
    $author_name=$request->author_name;
    $description=$request->description;
    $book_name=$request->book_name;
    
    $email= Auth::user()->email;
  
    
    $user=DB::table('books')
    ->where('books_id',$book_id)
    ->select('email')
    ->first();

 
    
    if($user->email!=$email){
    return response("you are not authorized to edit this book",401);
    }
    DB::table('books')
    ->where('books_id',$book_id)
    ->update([
    "author_name"=>$author_name,
    "description"=>$description,
    "book_name"=>$book_name,
    ]);
}
    function editProfile(Request $request){
     
    //    $validation= $request->validate([
    //         'name' => 'required',
    //         'latitude' => 'required',
    //         'longitude'=>'required'
    //     ]);
       $validator= Validator::make($request->all(),[
            'name' => 'required',
            'latitude' => 'required',
            'longitude'=>'required'
       ]);

       

        if($validator->fails()){
            
            return response()->json($validator->errors(), 400);

        }
        else{
        $email = Auth::user()->email;
        $name = $request->name;
        $latitude=$request->latitude;
        $longitude=$request->longitude;
        if($email==null){
            return "please login to change the profile";
        }
        DB::table('users')
        ->where('email',$email)
        ->update([
            'name'=>$name,
            'latitude'=>$latitude,
            'longitude'=>$longitude
        ]);



     return response("Profile Updated",200);
    }
    }
    function myProfile(){
        $email=Auth::user()->email;
        //$email="jack@gmail.com";
        
        
        if($email==null){
            return "please login to view profile";
        }

                    $books=DB::table('books')
                        ->where('email',$email)
                        ->get();

        if(count($books)==0){
            
          return DB::table('users')
          ->select('name','email','latitude','longitude')
            ->where('users.email',$email)
            ->get();
        }

        $user = DB::table('users')
        ->where('users.email',$email)
        ->join('books','books.email','users.email')
        ->get();



        return $user;
    }        
    function myBooks(){
        $email=Auth::user()->email;
        return DB::table('books')
        ->where('email',$email)
        ->where('display',1)
        ->get();
        }
        


    function check(Request $request){
        //$request->checkbox;
        
        DB::table('notification')
        ->insert (
        ['req_book_title'=>"asfkjlff",
        'req_book_authors_name' => "slkfskjlf",
        'req_book_description'=>"skljfklf",
          'send_to'=>"sjfskfls",
          'book_id'=>13,
          'status'=>2,
          'req_book_ids'=>json_encode($request->checkbox),
          'send_from'=>"klsjflk"]);
      
    }
    function getMyInfo(Request $request)
    {
        $email = $request->email;
        
        // return DB::table('users')
        // ->join('books','users.email','=','books.email')
        //     ->where('email', $email)
        //     ->get();

        return DB::table('users')
        ->where('email',$email)
        ->get();

        // return  DB::table('books')
        // ->where('email',$email)
        // ->get();
    }
}
