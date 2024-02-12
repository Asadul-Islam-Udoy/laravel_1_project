<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\GroupVieoFile;

class Video extends Model
{   use HasUuids;
    use HasFactory;
    protected $fillable=['title','video','description','user_id','groupname'];
    public function groupsfile(){
        return $this->hasMany(GroupVieoFile::class,'video_id');
    }
}
