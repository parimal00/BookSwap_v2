<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\User;
class UploadBookController extends Controller
{
  public function search(Request $request)
  {

    $searchedData = $request->searchedData;
    return DB::table('books')
      ->where('book_name', 'like', '%' . $searchedData . '%')
      ->orWhere('author_name', 'like', '%' . $searchedData . '%')
      ->paginate(3);
  }

  public function uploadLocation(Request $request)
  {

    $email = $request->email;
    $latitude = $request->latitude;
    $longitude = $request->longitude;
    DB::table('users')
      ->where('email', $email)
      ->update([
        "latitude" => $latitude,
        "longitude" => $longitude
      ]);
  }

  public function insertLocation(Request $request)
  {
    $email = $request->email;
    $latitude = $request->latitude;
    $longitude = $request->longitude;
    DB::table('users')
      ->where('email', $email)
      ->update([
        "longitude" => $longitude,
        "latitude" => $latitude
      ]);
  }
  public function testLogin(Request $request)
  {
    $email = $request->email;
    $firstname = $request->firstname;

    $user = User::where('email', $email)->first();

  
      


    // if (count($user) == 0) {
    //   DB::table('users')
    //     ->insert([
    //       "email" => $email,
    //       "name" => $firstname,
    //       "password" => "waaaaa"
    //     ]);

    //   $pusherPath = md5(uniqid(rand(), true));
    //   DB::table('pusher_path')
    //     ->insert([
    //       "path" => $pusherPath,
    //       "email" => $email
    //     ]);
    // } else {
    // }
    Auth::login($user);
    return view('welcome', ['email' => $email]);
  }


  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('home');
  }

  //
  function check()
  {

    $token = md5(uniqid(rand(), true));

    $row = DB::table('auhorization')
      ->where('user_id', 1)
      ->get();
    if (count($row) == 0) {
      DB::table('auhorization')
        ->insert([
          'user_id' => 1,
          'token' => $token
        ]);
    } else {
      DB::table('auhorization')
        ->where('user_id', 1)
        ->update([
          'token' => $token

        ]);
    }
  }

  function test()
  {
    echo session('user_id');
  }
  function insertSession(Request $req, $id)
  {
    echo $id;
    $req->session()->put('user_id', $id);

    return view('welcome');
  }
  function getMessages()
  {

    $user_id = 1;
    return DB::table('messages')
      ->join('users', 'users.id', 'msg_from')
      ->where('msg_to', $user_id)
      ->get();
  }

  function rejectReq(Request $request)
  {
    $notification_id = $request->notification_id;
    $books =  $request->books;
    $radio = $request->radio;
    $send_from = $request->send_from;
    $send_to = $request->send_to;

    DB::table('notification')
      ->where('notification_id', $notification_id)
      ->update([
        "send_from" => $send_from,
        "send_to" => $send_to
      ]);
    foreach ($books as $book) {
      DB::table('books')
        ->where('books_id', $book)
        ->update([
          "display" => 1
        ]);
    }


    return DB::table('notification')
      ->where('notification_id', $notification_id)
      ->update([
        "status" => 0
      ]);
  }

  function acceptSingleReq(Request $request)
  {
    $notification_id = $request->notification_id;

    $send_from = $request->send_from;
    $send_to = $request->send_to;
    $book_id = $request->book_id;
    $sender_book_id = $request->sender_book_id;


    DB::table('notification')
      ->where('notification_id', $notification_id)
      ->update([
        "status" => 1,
        "send_to" => $send_to,
        "send_from" => $send_from,
        "acc_book_id" => $sender_book_id
      ]);
    DB::table('books')
      ->where('books_id', $sender_book_id)
      ->update([
        "display" => 0
      ]);
    DB::table('books')
      ->where('books_id', $book_id)
      ->update([
        "display" => 0
      ]);
  }
  function acceptReq(Request $request)
  {
    $notification_id = $request->notification_id;
    $books =  $request->books;
    $radio = $request->radio;
    $send_from = $request->send_from;
    $send_to = $request->send_to;
    $book_id = $request->book_id;


   



    DB::table('notification')
      ->where("notification_id", $notification_id)
      ->update([
        "send_to" => $send_to,
        "send_from" => $send_from
      ]);
    foreach ($books as $book) {
      DB::table('books')
        ->where('books_id', $book)
        ->update([
          "display" => 1
        ]);
    }

    DB::table('notification')
      ->where("notification_id", $notification_id)
      ->update([
        "acc_book_id" => $radio
      ]);



    DB::table('books')
      ->where('books_id', $radio)
      ->update([
        "display" => 0
      ]);

    DB::table('books')
      ->where("books_id", $book_id)
      ->update([
        "display" => 0
      ]);
    // foreach($books as $book){
    //   echo $book->book_name;
    // }


    DB::table('notification')
      ->where('notification_id', $notification_id)
      ->update(
        [
          "status" => 1
        ]
      );
  }
  function viewBooks(Request $request)
  {


    // return DB::table('books')
    // ->where('display',1)
    // ->join('users', 'books.email', '=', 'users.email')
    // ->paginate(3);

    $latitude = $request->latitude;
    $longitude = $request->longitude;

    $lat = $latitude / 57.29577951;
    $long = $longitude / 57.29577951;


    return DB::table('books')
      ->where('display', 1)
      ->join('users', 'books.email', '=', 'users.email')
      ->Select("*", DB::raw('3963.0 * acos(sin(users.latitude/57.29577951) * sin(' . $lat . ')+ cos(users.latitude/ 57.29577951) * cos(' . $lat . ') *cos(' . $long . '-users.longitude/ 57.29577951)) AS distance'))
      ->orderBy('distance')
      ->paginate(3);



    // $data = DB::table("users")
    //     ->select("users.id"
    //         ,DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
    //         * cos(radians(users.lat)) 
    //         * cos(radians(users.lon) - radians(" . $lon . ")) 
    //         + sin(radians(" .$lat. ")) 
    //         * sin(radians(users.lat))) AS distance"))
    //         ->groupBy("users.id")
    //         ->get();





    // return  DB::table('books')
    //   ->where('display', 1)
    //   ->join('users', 'books.email', '=', 'users.email')
    //   ->get();

  }

  function uploadBooks(Request $request)
  {

    $email=Auth::user()->email;
    if($email==null){
      return response("please login to upload this book");
    }
    $rules = [
     
     
      "bookTitle" => 'required',
      "bookAuthor" => 'required',
      "description"=>'required',

    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }



    //     $request->file('bookImage')->store('img');

    //    echo $request->file('bookImage')->getClientOriginalName();

    //  echo    $extension = $request->file('bookImage')->getClientOriginalExtension();
    // return $request->file('bookImage')->getClientOriginalName();
    // echo  $request->file('error');
    //dd($request->file('bookImage'));
    $image_path = md5(uniqid(rand(), true));
    $userId = 1;
    // echo $request->bookTitle;
    // echo $request->description;
    // echo $request->bookAuthor;
    // echo $request->file('bookImage')->getClientOriginalExtension();
    // echo $request->file('bookImage')->getClientOriginalName();

    //return $request->bookTitle;


    $image = $request->file('bookImage');

    $book = Image::make($image);


    $destinationPath = public_path('/uploads');

    $book->save($destinationPath . '/' . $image_path);

    //$request->file('bookImage')->move('uploads', $image_path);



    $latitude = $request->latitude;

    $longitude = $request->longitude;
    

    $users = DB::table('users')
      ->where('email', $email)
      ->get();

    // foreach ($users as $usr) {

    //   DB::table('users')
    //     ->where('email', $email)
    //     ->update([
    //       "latitude" => $latitude,
    //       "longitude" => $longitude
    //     ]);

  
    // }

    
  

    return DB::table('books')
      ->insert([
        "book_name" => $request->bookTitle,
        "email" => $email,
        "author_name" => $request->bookAuthor,
        "description" => $request->description,
        "book_image" => $image_path,
        "display" => 1

      ]);
    // $books = DB::table('books')
    //   ->get();
    // return view('home_page',['books'=>$books]);

  }

  function testsubmit(Request $request)
  {

    echo $request->test;
    echo $request->test2;
  }

  function sendNotification(Request $req)
  {

    $req_book_ids = json_encode($req->checkBox);
    $send_to = $req->send_to;



    DB::table('notification')
      ->insert(
        [
          'send_to' => $send_to,
          'req_book_ids' => $req_book_ids,
          'book_id' => $req->book_id,
          'status' => 2,
          'send_from' => $req->send_from
        ]
      );

    $notifications = DB::table('notification')
      ->where('send_to', $send_to)
      ->where('send_from', $req->send_from)
      ->orderBy('notification_id', 'desc')
      ->first();

    $notf_id = $notifications->notification_id;



    foreach ($req->checkBox as $book_id) {
      DB::table('books')
        ->where('books_id', $book_id)
        ->update([
          "display" => 0
        ]);


      DB::table('not_book_tab')
        ->insert([
          "book_ref_id" => $book_id,
          "not_ref_id" => $notf_id,
        ]);
    }

    return "book inserted";
  }

  function getNotification(Request $request)
  {
    $array1 = [];
    $array2 = [];
    $email = $request->email;

    // $notifications = DB::table('notification')
    //   ->where('send_to', $email)
    //   ->get();

    // foreach ($notifications as $notification) {
    //   $abcd = $notification->acc_book_id;
    //   if(is_null($abcd)){
    //     return "jack is sexy";
    //   }
    //   else{
    //  return "waaaaaaa";
    //   }
    // }



    $notifications = DB::table('notification')
      ->where('send_to', $email)
      ->orWhere('send_from', $email)
      ->get();

    foreach ($notifications as $notification) {
      $notification_id = $notification->notification_id;
      $books = DB::table('not_book_tab')
        ->where('not_ref_id', $notification_id)
        ->join('books', 'books_id', 'book_ref_id')
        ->get();
      $array2 = ['heyy', 'there'];


      $abcd = $notification->acc_book_id;
      $acc_book_idz = $notification->acc_book_id;
      $rej_book_idz = $notification->rej_book_id;
      $rejBook = 0;
      $accBook = 1;


      if (is_null($acc_book_idz)) {
        $accBook = null;
      } else {

        $accBook = DB::table('books')
          ->where('books_id', $notification->acc_book_id)
          ->get();
      }
      if (is_null($rej_book_idz)) {
        $rejBook = null;
      } else {
        $rejBook = DB::table('books')
          ->where('books_id', $notification->rej_book_id)
          ->get();
      }

      $myBook = DB::table('books')
        ->where('books_id', $notification->book_id)
        ->get();






      array_push($array1, array("myBooks" => $myBook, "book_id" => $notification->book_id, "notification_id" => $notification->notification_id, "status" => $notification->status, "send_from" => $notification->send_from, "send_to" => $notification->send_to, "books" => $books, "acc_book" => $accBook, "rej_book" => $rejBook));
    }

    return $array1;
  }
}
