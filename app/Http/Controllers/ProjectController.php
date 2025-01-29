<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\Models\Style;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Enums\ApiResponse;

class ProjectController extends Controller
{
    protected $projectService;

    public function index(){
        return ProjectResource::collection(
            Project::query()->orderBy('idP')->get()
        );
    }    

    public function createPageFromTemplate(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|exists:projects,idP',
            'template_id' => 'required|exists:templates,id'
        ]);

        $template = Template::findOrFail($validatedData['template_id']);
        $project = Project::findOrFail($validatedData['project_id']);

        $page = $project->pages()->create([
            'title' => $template->name,
            'html_content' => $template->html_content,
            'css_content' => $template->css_content
        ]);

        return response()->json([
            'project' => $project,
            'page' => $page,
            'template' => $template
        ]);
    }

    public function show($id){
        $project = Project::find($id);
        if(!$project){
            return response(['STATE'=>ApiResponse::NOT_FOUND]);
        }
        return new ProjectResource($project);
    }

    public function getProjectsByUser($id){
        $user = User::find($id);
        if(!$user){
            return response(['message'=>'user NotFound','STATE' => ApiResponse::NOT_FOUND]);
        }

        return ProjectResource::collection($user->projects);
    }

    public function store(Request $request)
    {
        return $this->projectService->addProject($request->validated());
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'projectName' => 'required|string|max:255',
            'projectDescription' => 'nullable|string',
            'websiteTitle' => 'nullable|string|max:255',
            'repoUrl' => 'nullable|url',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
            'project_type' => 'nullable|string|max:255',
            'style' => 'nullable|array',
            'style.primary_color' => 'nullable|string',
            'style.secondary_color' => 'nullable|string',
        ]);
    
        $project = new Project();
        $project->title = $validatedData['projectName'];
        $project->description = $validatedData['projectDescription'] ?? null;
        $project->domaineName = $validatedData['websiteTitle'] ?? null;
        $project->repository = $validatedData['repoUrl'] ?? null;
        $project->user_id = $validatedData['user_id'];
        $project->project_type = $validatedData['project_type'] ?? null;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/projects'), $imageName);
            $project->image_url = 'uploads/projects/' . $imageName;
        } else {
            $project->image_url = '';  
        }

        // Create style if provided
        if (isset($validatedData['style'])) {
            $style = new Style($validatedData['style']);
            $style->save();
            $project->style_id = $style->id;
        }
    
        if ($project->save()) {
            return response([
                'STATE' => ApiResponse::OK,
                'data' => new ProjectResource($project->load('style')),
            ]);
        }
    
        return response(['STATE' => ApiResponse::ERROR]);
    }

    public function update(Request $request, $id)
{
    $project = Project::find($id);

    if (!$project) {
        return response(['STATE' => ApiResponse::NOT_FOUND]);
    }

    // Validate the request
    $validated = $request->validate([
        'title' => 'nullable|string',
        'description' => 'nullable|string',
        'domaineName' => 'nullable|string',
        'repository' => 'nullable|string',
        'project_type' => 'nullable|string',
        'template_id' => 'nullable|exists:templates,id',
        'style_id' => 'nullable|exists:styles,id',  // Add validation for style_id
        'style' => 'nullable|array',
        'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle direct style_id update
    if ($request->has('style_id')) {
        $project->style_id = $request->input('style_id');
    }

    // Handle other fields
    $fillableFields = ['title', 'description', 'domaineName', 'repository', 'project_type', 'template_id'];
    foreach ($fillableFields as $field) {
        if ($request->has($field)) {
            $project->{$field} = $request->input($field);
        }
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/projects'), $imageName);
        $project->image_url = 'uploads/projects/' . $imageName;
    }

    // Handle style update
    if ($request->has('style')) {
        $styleData = $request->input('style');
        
        if ($project->style_id) {
            $style = Style::findOrFail($project->style_id);
            $style->update($styleData);
        } else {
            $style = new Style($styleData);
            $style->save();
            $project->style_id = $style->id;
        }
    }

    try {
        $project->save();
        
        return response([
            'STATE' => ApiResponse::OK,
            'data' => new ProjectResource($project->load('style')),
        ]);
    } catch (\Exception $e) {
        \Log::error('Project update failed: ' . $e->getMessage());
        return response([
            'STATE' => ApiResponse::ERROR,
            'message' => 'Failed to update project',
            'error' => $e->getMessage()
        ]);
    }
}

    public function destroy($id){
        $project = Project::find($id);
        if(!$project){
            return response(['message'=>'project NotFound' , 'STATE' => ApiResponse::NOT_FOUND]);
        }
        if($project->delete()){
            return response(['STATE' => ApiResponse::OK]);
        }else{
            return response(['STATE' => ApiResponse::ERROR]);
        }
    }
}