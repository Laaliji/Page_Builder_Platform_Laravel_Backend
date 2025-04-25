<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'image',
        'bio',
        'location',
        'website',
        'total_projects',
        'last_project_created_at',
        'preferences'
    ];

    protected $casts = [
        'preferences' => 'array',
        'last_project_created_at' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * Increment the total projects count
     */
    public function incrementProjectCount()
    {
        $this->total_projects += 1;
        $this->last_project_created_at = now();
        $this->save();
    }
}
