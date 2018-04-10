<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    //

    protected $table = "tbl_info";

    /**
     * @return mixed
     * @paginate
     * data from info
     */
    public function getName(){
        return Info::paginate(5);
    }

    /**
     * @all
     * get all collection of data
     */
    public function getAll(){
        return Info::all();
    }


}
