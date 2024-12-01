<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $primaryKey = 'idComponent';

    protected $fillable = [
        'typeComponent',
        'position',
        'size',
        'style',
        'page_id'
    ];

    protected $casts = [
        'position' => 'array',
        'size' => 'array'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id', 'idPage');
    }
}