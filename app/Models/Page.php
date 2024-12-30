<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'html_page_title',
        'html_content',
        'css_content',
        'project_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'idP');
    }
}

