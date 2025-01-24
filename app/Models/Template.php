<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'html_content',
        'css_content',
        'description',
        
    ];

    
    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }
}