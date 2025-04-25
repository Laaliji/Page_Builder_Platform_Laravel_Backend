<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'preview_image_url',
        'html_content',
        'css_content'
    ];

    /**
     * Create a new page from this template
     * 
     * @param string $projectId
     * @param string $pageId
     * @param string $pageTitle
     * @return Page
     */
    public function createPage(string $projectId, string $pageId, string $pageTitle = null): Page
    {
        return Page::create([
            'id' => $pageId,
            'title' => $pageTitle ?? $this->title,
            'html_page_title' => $pageTitle ?? $this->title,
            'html_content' => $this->html_content,
            'css_content' => $this->css_content,
            'project_id' => $projectId
        ]);
    }
}
