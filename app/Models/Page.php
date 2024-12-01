<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPage';

    protected $fillable = [
        'title',
        'urlPage',
        'project_id'
    ];

    public function components()
    {
        return $this->hasMany(Component::class, 'page_id', 'idPage');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'idP');
    }
}

