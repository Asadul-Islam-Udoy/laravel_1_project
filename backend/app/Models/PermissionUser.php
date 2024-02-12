<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PermissionUser extends Model
{
    use HasUuids;
    use HasFactory;
    protected $fillable=['name','user_id','user_create','user_delete','user_update','user_get']; 
    
}
