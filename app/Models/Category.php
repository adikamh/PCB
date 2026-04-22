<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}