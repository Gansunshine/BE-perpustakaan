<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Book extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['title', 'summary', 'image', 'stok', 'category_id', 'cloudinary_public_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
