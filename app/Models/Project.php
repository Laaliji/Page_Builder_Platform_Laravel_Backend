<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'idP';

    protected $fillable = [
        'title',
        'domaineName',
        'repository'
    ];

public function pages(){
    return $this->hasMany(Page::class, 'project_id','idP');
}

}