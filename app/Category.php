<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function products(){
	return $this->belongsToMany(Product::class);
    }

     public function childrens(){
        return $this->belongsToMany(Category::class,'category_parent','parent_id','category_id');
    }
    public function parents(){
        return $this->belongsToMany(Category::class,'category_parent','category_id','parent_id');
    }
}
