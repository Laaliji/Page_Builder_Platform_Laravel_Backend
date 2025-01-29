<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'quaternary_color',
        'background_color',
        'text_color',
        'is_dark_mode'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}