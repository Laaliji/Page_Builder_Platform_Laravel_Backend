<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['show']);
    }

    public function index()
    {
        $user = Auth::user();
        return ProjectResource::collection(
            $user->projects()->orderBy('created_at', 'desc')->get()
        );
    }

    public function show($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Project not found'
            ], 404);
        }

        return Cache::remember("Project_{$id}", now()->addMinutes(30), function () use ($project) {
            return new ProjectResource($project);
        });
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'domaineName' => 'required|string|max:255|unique:projects,domaineName',
            'repository' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();
            
            $project = new Project();
            $project->title = $request->title;
            $project->description = $request->description;
            $project->domaineName = $request->domaineName;
            $project->repository = $request->repository;
            $project->user_id = $user->id;
            $project->image_url = '';

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('projects', 'public');
                $project->image_url = Storage::url($imagePath);
            }

            if ($project->save()) {
                // Update user profile
                if ($user->userProfile) {
                    $user->userProfile->incrementProjectCount();
                } else {
                    // Create user profile if it doesn't exist
                    $user->userProfile()->create([
                        'total_projects' => 1,
                        'last_project_created_at' => now()
                    ]);
                }

                return response()->json([
                    'STATE' => ApiResponse::OK,
                    'data' => new ProjectResource($project),
                ], 201);
            }

            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Failed to create project'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Project creation failed: ' . $e->getMessage());
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function getProjectsByUser($id)
    {
        $user = Auth::user();
        
        // Users can only access their own projects unless admin
        if ($user->id != $id) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return ProjectResource::collection($user->projects()->orderBy('created_at', 'desc')->get());
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Project not found'
            ], 404);
        }

        // Check ownership
        if ($project->user_id !== Auth::id()) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Handle partial updates (title only)
        if ($request->only('title') === $request->all()) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'STATE' => ApiResponse::INVALID_DATA,
                    'ERRORS' => $validator->errors()
                ], 422);
            }

            $project->title = $request->title;

            if ($project->save()) {
                Cache::forget("Project_{$id}");
                return response()->json(['STATE' => ApiResponse::OK]);
            }

            return response()->json(['STATE' => ApiResponse::ERROR], 500);
        }

        // Full update validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'domaineName' => 'required|string|max:255|unique:projects,domaineName,' . $id,
            'repository' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ], 422);
        }

        try {
            $project->title = $request->title;
            $project->description = $request->description;
            $project->domaineName = $request->domaineName;
            $project->repository = $request->repository;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($project->image_url) {
                    $oldPath = str_replace('/storage/', '', $project->image_url);
                    Storage::disk('public')->delete($oldPath);
                }

                $image = $request->file('image');
                $imagePath = $image->store('projects', 'public');
                $project->image_url = Storage::url($imagePath);
            }

            if ($project->save()) {
                Cache::forget("Project_{$id}");
                return response()->json([
                    'STATE' => ApiResponse::OK,
                    'data' => new ProjectResource($project)
                ]);
            }

            return response()->json(['STATE' => ApiResponse::ERROR], 500);
        } catch (\Exception $e) {
            Log::error('Project update failed: ' . $e->getMessage());
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Project not found'
            ], 404);
        }

        // Check ownership
        if ($project->user_id !== Auth::id()) {
            return response()->json([
                'STATE' => ApiResponse::NOT_FOUND,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            // Delete associated image
            if ($project->image_url) {
                $imagePath = str_replace('/storage/', '', $project->image_url);
                Storage::disk('public')->delete($imagePath);
            }

            // Delete associated pages
            $project->pages()->delete();

            Cache::forget("Project_{$id}");
            Cache::forget("Pages_{$id}");
            
            if ($project->delete()) {
                return response()->json(['STATE' => ApiResponse::OK]);
            }
            
            return response()->json(['STATE' => ApiResponse::ERROR], 500);
        } catch (\Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            return response()->json([
                'STATE' => ApiResponse::ERROR,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
