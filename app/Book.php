<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'category_id', 'location', 'bol_link', 'sold'];

    public function category(){
        return $this->belongsTo('App\Category');
    }
}
