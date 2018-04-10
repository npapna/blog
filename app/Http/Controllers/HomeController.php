<?php

namespace App\Http\Controllers;

use App\Info;
use App\Phone;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
       // $data = $info->get();

        /***
         * 2 query where in
         * first init get age 8
         * after that get age 10 depends on where IN
         *
         */
        $qr1 = Info::where('age','=',8)
                    ->orWhere(function ($q){
                        $q->where('age','=','10');
                        $q->whereIn('first_name',['N','P','O']);
                    })
            ->get()
            ->toArray();

        /***
         *
         * pluck row
         *
         */
        $qr2 = Info::where('id',32)->pluck('first_name');

        /**
         * @eloquent
         * user has one phone
         * one is to one relationship
         */






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
