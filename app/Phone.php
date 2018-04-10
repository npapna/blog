<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    //

    protected $table = "tbl_phone";

    /***
     * @eloquent belongTo
     */
    public function user(){
        return $this->belongsTo('App\User','user_id','id')
                ->withDefault(function($phone){
                    $phone->phone_name = "example";
                });
    }


}
