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
        'id',
        'title',
        'html_page_title',
        'html_content',
        'css_content',
        'project_id'
    ];

    protected $casts = [
        'title' => 'string',
        'html_page_title' => 'string',
        'html_content' => 'string',
        'css_content' => 'string',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'idP');
    }
}

