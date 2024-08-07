<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Profile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['bio', 'age', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
