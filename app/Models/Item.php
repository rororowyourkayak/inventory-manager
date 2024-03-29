<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['user', 'upc', 'category', 'description', 'quantity', 'user_id'];
    public $timestamps = true; 
    
    
}
