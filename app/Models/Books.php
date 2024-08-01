<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory, HasUlids;
    protected $table = 'books';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'summary',
        'image',
        'stock',
        'public_image_id',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function list_borrows(){
        return $this->hasMany(Borrows::class, 'book_id', 'id');
    }
}
