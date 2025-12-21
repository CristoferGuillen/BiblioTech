<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publication_year',
        'category_id',
        'copies_available',
        'status',
    ];

    protected $casts = [
        'publication_year' => 'date:Y',
    ];
    public function Categories (){

        return $this->belongsTo(Categories::class, 'category_id');
    }



}
