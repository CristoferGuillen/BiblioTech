<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

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
    public function Category (){

        return $this->belongsTo(Category::class, 'category_id');
    }



}
