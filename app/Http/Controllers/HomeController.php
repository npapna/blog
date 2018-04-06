<?php

namespace App\Http\Controllers;

use App\Info;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $set ="";

    public function __construct()
    {
        echo $this->set;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $info = new Info();
        return view('home',[
            'users'=>$info->getName()
        ]);
    }

    public function deleteInfo(Request $request){

        $info = Info::find($request->id);
        $info->delete();

        return response()->json([
            'response' => 'success'
        ]);

    }

    public function checkUser(Request $request){
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );
        $pusher = new \Pusher\Pusher(
            '8d58f12ccdbe64beb132',
            '6beccef13201599215fa',
            '504432',
            $options
        );



        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'     =>$validator->errors(),
                'response'  => 'error'
            ]);
        }else{

            $info = new Info();
            $info->first_name = $request->fname;
            $info->last_name = $request->lname;
            $info->save();


            $pusher->trigger('add-user', 'create-user',[
                'textdata'=> $request->fname
            ]);

            return response()->json([
                'data' => $request->fname.' '.$request->lname,
                'response' => 'success',
                'id' =>$info->id
            ]);
        }

    }

}
