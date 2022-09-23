<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'category_name'];

    public function children()
    {
      return $this->hasMany('App\Models\MaterialCategory', 'parent_id');
    }
    
    public function products()
    {
      return $this->hasMany('App\Models\Product');
    }
}
