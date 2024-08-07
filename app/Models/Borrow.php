<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Borrow extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['load_date', 'barrow_date', 'return_date', 'book_id', 'user_id'];

    protected $dates = ['load_date', 'barrow_date', 'return_date'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue()
    {
        return !$this->return_date && Carbon::parse($this->barrow_date)->setTimezone('Asia/Jakarta') < Carbon::now('Asia/Jakarta');
    }
}
