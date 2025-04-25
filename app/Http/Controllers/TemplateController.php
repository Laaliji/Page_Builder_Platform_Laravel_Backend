<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse;
use App\Models\Page;
use App\Models\Project;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /**
     * Display a listing of all templates.
     */
    public function index()
    {
        // Cache templates for performance
        $templates = Cache::remember('templates', now()->addDay(), function () {
            return Template::all();
        });

        return response()->json([
            'STATE' => ApiResponse::OK,
            'DATA' => $templates
        ]);
    }

    /**
     * Create a new page from a template.
     */
    public function createPageFromTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:templates,id',
            'project_id' => 'required|exists:projects,idP',
            'page_title' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }

        try {
            // Find the template
            $template = Template::findOrFail($request->template_id);
            
            // Generate a unique ID for the page
            $pageId = (string) Str::uuid();
            
            // Create a new page with template content
            $page = Page::create([
                'id' => $pageId,
                'title' => $request->page_title ?? $template->title,
                'html_page_title' => $request->page_title ?? $template->title,
                'html_content' => $template->html_content,
                'css_content' => $template->css_content,
                'project_id' => $request->project_id
            ]);

            // Clear cached pages for this project
            Cache::forget("Pages_{$request->project_id}");

            return response()->json([
                'STATE' => ApiResponse::OK,
                'DATA' => $page
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => "Failed to create page: {$e->getMessage()}"
            ], 500);
        }
    }

    /**
     * Display the specified template with full content.
     */
    public function show($id)
    {
        try {
            $template = Template::findOrFail($id);
            
            return response()->json([
                'STATE' => ApiResponse::OK,
                'DATA' => $template
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'MESSAGE' => 'Template not found'
            ], 404);
        }
    }

    /**
     * Apply a template to an existing page.
     */
    public function applyTemplateToPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:templates,id',
            'page_id' => 'required|exists:pages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }

        try {
            $template = Template::findOrFail($request->template_id);
            $page = Page::findOrFail($request->page_id);
            
            // Update the page with template content
            $page->update([
                'html_content' => $template->html_content,
                'css_content' => $template->css_content
            ]);
            
            // Clear cache
            Cache::forget("Pages_{$page->project_id}");
            
            return response()->json([
                'STATE' => ApiResponse::OK,
                'MESSAGE' => 'Template applied successfully',
                'DATA' => $page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => "Failed to apply template: {$e->getMessage()}"
            ], 500);
        }
    }
}
