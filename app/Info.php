<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    //

    protected $table = "tbl_info";

    public function getName(){
        return Info::paginate(5);
    }
}
