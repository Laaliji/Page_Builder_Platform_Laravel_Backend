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
   
    public function projects()  // One template can be used by many projects
    {
        return $this->hasMany(Project::class, 'template_id', 'id');
    }
}
