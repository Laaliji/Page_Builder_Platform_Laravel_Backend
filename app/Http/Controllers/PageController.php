<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse;
use App\Models\Page;
use App\Models\Project;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['getSharedPage']);
    }

    public function index($projectId)
    {
        // Verify project ownership
        $project = Project::where('id', $projectId)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Project not found or unauthorized'
            ], 404);
        }

        $pages = Cache::remember("Pages_{$projectId}", now()->addMinutes(30), function () use ($projectId) {
            return Page::where('project_id', $projectId)->get();
        });

        return response()->json([
            'STATE' => ApiResponse::OK,
            'DATA' => $pages
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'nullable|string|max:255',
            'html_page_title' => 'nullable|string|max:255',
            'html_content' => 'nullable|string',
            'css_content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }

        // Verify project ownership
        $project = Project::where('id', $request->project_id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Project not found or unauthorized'
            ], 403);
        }

        try {
            $pageId = $request->id ?? Str::uuid()->toString();
            
            $page = Page::create([
                'id' => $pageId,
                'title' => $request->title ?? 'New Page',
                'html_page_title' => $request->html_page_title ?? $request->title ?? 'New Page',
                'html_content' => $request->html_content ?? '',
                'css_content' => $request->css_content ?? '',
                'project_id' => $request->project_id
            ]);

            Cache::forget("Pages_{$request->project_id}");

            return response()->json([
                'STATE' => ApiResponse::OK,
                'DATA' => $page
            ], 201);
        } catch (\Exception $e) {
            Log::error('Page creation failed: ' . $e->getMessage());
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => 'Failed to create page'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $page = Page::find($id);
        
        if (!$page) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Page not found'
            ], 404);
        }

        // Verify project ownership
        $project = Project::where('id', $page->project_id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $page->html_content = $request->html_content ?? $page->html_content;
            $page->css_content = $request->css_content ?? $page->css_content;
            
            if ($page->save()) {
                Cache::forget("Pages_{$page->project_id}");
                return response()->json(['STATE' => ApiResponse::OK]);
            }
            
            return response()->json(['STATE' => ApiResponse::ERROR], 500);
        } catch (\Exception $e) {
            Log::error('Page update failed: ' . $e->getMessage());
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePageMetaData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'html_page_title' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }

        $page = Page::find($id);
        
        if (!$page) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Page not found'
            ], 404);
        }

        // Verify project ownership
        $project = Project::where('id', $page->project_id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $page->title = $request->title;
            $page->html_page_title = $request->html_page_title ?? $request->title;
            
            if ($page->save()) {
                Cache::forget("Pages_{$page->project_id}");
                return response()->json(['STATE' => ApiResponse::OK]);
            }
            
            return response()->json(['STATE' => ApiResponse::ERROR], 500);
        } catch (\Exception $e) {
            Log::error('Page metadata update failed: ' . $e->getMessage());
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        
        if (!$page) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Page not found'
            ], 404);
        }

        // Verify project ownership
        $project = Project::where('id', $page->project_id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            Cache::forget("Pages_{$page->project_id}");
            
            if ($page->delete()) {
                return response()->json(['STATE' => ApiResponse::OK]);
            }
            
            return response()->json(['STATE' => ApiResponse::ERROR], 500);
        } catch (\Exception $e) {
            Log::error('Page deletion failed: ' . $e->getMessage());
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'MESSAGE' => $e->getMessage()
            ], 500);
        }
    }

    public function getSharedPage($sharedLink)
    {
        $project = Project::where('shared_link', $sharedLink)->first();
        
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Shared page not found'
            ], 404);
        }

        $pages = $project->pages;
        
        return response()->json([
            'STATE' => ApiResponse::OK,
            'DATA' => [
                'project' => $project,
                'pages' => $pages
            ]
        ]);
    }
}