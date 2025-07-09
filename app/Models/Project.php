<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'idP';

    protected $fillable = [
        'title',
        'description',
        'domaineName',
        'repository',
        'image_url',
        'user_id',
        'shared_link'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (empty($project->shared_link)) {
                $project->shared_link = Str::uuid()->toString();
            }
        });
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'project_id', 'idP');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
