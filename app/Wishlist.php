<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public function wishlists(){
        return $this->hasMany('App\Wishlist','id');
    }
}