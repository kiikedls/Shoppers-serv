<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function items(){
        return $this->hasMany(Item::class);
    }

    public function addCategory(Category $category){
        return $this->categories()->save($category);
    }

    public function deleteCategory($categoryId){
        $this->categories()->find($categoryId)->delete();
        return ["message"=>"la lista de compras fue borrada"];
    }

    public function hasDuplicateCategory($categoryName){
        return $this->categories()->where("name",$categoryName)->count();
    }

    public function addItem(Item $item,$categoryId){
        return $this->categories()->find($categoryId)
        ->items()->create([
            "name"=>$item->name,
            "description"=>$item->description,
            "user_id"=>$this->id
        ]);
    }

    public function hasDuplicateItem($categoryId,$itemName){
        return $this->categories()->find($categoryId)->items()->where("name",$itemName)->count();
    }

}
