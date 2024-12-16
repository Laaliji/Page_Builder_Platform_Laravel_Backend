<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'idP';

    protected $fillable = [
        'title',
        'domaineName',
        'repository',
        'image_url',
        'user_id'
    ];


    public function pages(){
        return $this->hasMany(Page::class, 'project_id','idP');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}