<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=array('user_id','name');
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function addItem(Item $item)
    {
        return $this->items()->save($item);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
