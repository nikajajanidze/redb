<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $table = 'entries';

    /**
     * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'user_id', 'meal', 'calories', 'extra_field'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByRole($query, $user)
    {
        return $query->whereHas('user', function($q) use ($user){
            if($user->role == 'manager') {
                $q->where('id', $user->id)
                ->orWhere('role', 'user');
            }

            if($user->role == 'user') {
                $q->where('id', $user->id);
            }
        });
    }
}
