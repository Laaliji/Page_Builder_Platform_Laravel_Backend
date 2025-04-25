<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse;
use App\Models\Page;
use App\Models\Project;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function showPage($id){
        $page = Page::where('id', $id)->first();

        if($page){
            return response([
                "STATE" => ApiResponse::OK,
                "DATA" => $page,
            ]);
        }
        
        return response([
            "STATE" => ApiResponse::ERROR,
        ]);
        
    }

    public function show($id) {
        $CachedPages = Cache::remember("Pages_{$id}",now()->addMinutes(30), function () use ($id) {
            return Page::where('project_id', $id)->get();
        });

        return response([
            "STATE" => ApiResponse::OK,
            "DATA" => $CachedPages,
        ]);
    }

    public function showPagesShared($id){
        $project = Project::where('shared_link', $id)->first();
        if($project){
            $pages = Page::where('project_id', $project->idP)->get();
            return response([
                "STATE" => ApiResponse::OK,
                "DATA" => $pages,
            ]);
        }
        
        return response([
            "STATE" => ApiResponse::ERROR,
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,idP',
            'id' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'html_page_title' => 'nullable|string|max:255',
            'html_content' => 'nullable|string',
            'css_content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }
        
        try {
            // Generate ID if not provided
            if (!$request->has('id') || $request->id === null) {
                $request->merge(['id' => Str::uuid()->toString()]);
            }

            // Create the page with only project_id as required
            $page = Page::create($request->all());

            if($page){
                Cache::forget("Pages_{$page->project_id}");
                return response([
                    "STATE" => ApiResponse::OK,
                    "DATA" => $page
                ]);
            }
            
            return response([
                "STATE" => ApiResponse::ERROR,
            ]);
        } catch (\Exception $e) {
            return response([
                "STATE" => ApiResponse::ERROR,
                "MESSAGE" => $e->getMessage(),
            ]);
        }
    }

    public function updatePageMetaData(Request $request, $id) {
        $page = Page::find($id);
        $page->title = $request->title;
        $page->html_page_title = $request->html_page_title;
        if($page->save()){
            Cache::forget("Pages_{$page->project_id}");
            return response([
                "STATE" => ApiResponse::OK,
            ]);
        }else{
            return response([
                "STATE" => ApiResponse::ERROR,
            ]);
        }
    }
    public function update(Request $request, $id) {
        try{
            $page = Page::find($id);
            $page->html_content = $request->html_content;
            $page->css_content  = $request->css_content;
            if($page->save()){
                Cache::forget("Pages_{$page->project_id}");
                return response([
                    "STATE" => ApiResponse::OK,
                ]);
            }else{
                return response([
                    "STATE" => ApiResponse::ERROR,
                ]);
            }
        }catch(\Exception $e){
            return response([
                "STATE" => ApiResponse::ERROR,
                "MESSAGE" => $e->getMessage(),
            ]);
        }
    }


    public function ExistePages($id){
        $pages = Page::where('project_id', $id)->get();
        return response([
            "EXISTE" => $pages->count() > 0,
        ]);
    }

    public function destroy($id) {
        $page = Page::find($id);
        if($page->delete()){
            Cache::forget("Pages_{$page->project_id}");
            return response([
                "STATE" => ApiResponse::OK,
            ]);
        }else{
            return response([
                "STATE" => ApiResponse::ERROR,
            ]);
        }
    }

    /**
     * Create a new page directly without template
     */
    public function createPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,idP',
            'title' => 'nullable|string|max:255',
            'html_page_title' => 'nullable|string|max:255',
            'html_content' => 'nullable|string',
            'css_content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }

        try {
            // Generate a unique ID for the page
            $pageId = Str::uuid()->toString();
            
            // Create the page with provided data
            $page = Page::create([
                'id' => $pageId,
                'title' => $request->title,
                'html_page_title' => $request->html_page_title ?? $request->title,
                'html_content' => $request->html_content,
                'css_content' => $request->css_content,
                'project_id' => $request->project_id
            ]);

            // Clear cached pages for this project
            Cache::forget("Pages_{$request->project_id}");

            return response([
                'STATE' => ApiResponse::OK,
                'DATA' => $page
            ], 201);
        } catch (\Exception $e) {
            return response([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => "Failed to create page: {$e->getMessage()}"
            ], 500);
        }
    }
    
    /**
     * Create a new page from a template
     */
    public function createPageFromTemplate(Request $request)
    {
        Log::info('========= CREATE PAGE FROM TEMPLATE STARTED =========');
        Log::info('Request data received:', [
            'all_data' => $request->all(),
            'template_id' => $request->template_id,
            'project_id' => $request->project_id,
            'page_title' => $request->page_title
        ]);
        
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:templates,id',
            'project_id' => 'required|exists:projects,idP',
            'page_title' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed with errors:', ['errors' => $validator->errors()->toArray()]);
            return response([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }

        try {
            // Retrieve the template
            $template = Template::findOrFail($request->template_id);
            Log::info('Template retrieved successfully:', [
                'template_id' => $template->id,
                'template_title' => $template->title,
                'template_description' => $template->description,
                'html_content_sample' => substr($template->html_content, 0, 100) . '...',
                'css_content_sample' => substr($template->css_content, 0, 100) . '...',
                'html_content_length' => strlen($template->html_content),
                'css_content_length' => strlen($template->css_content)
            ]);
            
            // Generate a unique ID for the page
            $pageId = Str::uuid()->toString();
            Log::info('Generated page ID:', ['page_id' => $pageId]);
            
            $pageData = [
                'id' => $pageId,
                'title' => $request->page_title ?? $template->title,
                'html_page_title' => $request->page_title ?? $template->title,
                'html_content' => $template->html_content,
                'css_content' => $template->css_content,
                'project_id' => $request->project_id
            ];
            
            Log::info('Page data prepared for creation:', [
                'page_id' => $pageId,
                'page_title' => $pageData['title'],
                'project_id' => $request->project_id,
                'html_page_title' => $pageData['html_page_title'],
                'html_content_length' => strlen($pageData['html_content']),
                'css_content_length' => strlen($pageData['css_content'])
            ]);
            
            // Create the page with content from the template
            $page = Page::create($pageData);
            Log::info('Page created successfully:', [
                'page_id' => $page->id,
                'page_created_at' => $page->created_at
            ]);

            // Clear cached pages for this project
            Cache::forget("Pages_{$request->project_id}");
            Log::info('Project cache cleared');
            
            Log::info('========= CREATE PAGE FROM TEMPLATE COMPLETED SUCCESSFULLY =========');
            return response([
                'STATE' => ApiResponse::OK,
                'DATA' => $page
            ], 201);
        } catch (\Exception $e) {
            Log::error('Exception occurred during page creation:', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Log::info('========= CREATE PAGE FROM TEMPLATE FAILED =========');
            return response([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => "Failed to create page from template: {$e->getMessage()}"
            ], 500);
        }
    }
} 