<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
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

        // Create first page using template
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
    
        if ($project->save()) {
            return response([
                'STATE' => ApiResponse::OK,
                'data' => new ProjectResource($project),
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
    
        
        if ($request->has('title')) {
            $project->title = $request->input('title');
        }
        if ($request->has('description')) {
            $project->description = $request->input('description');
        }
        if ($request->has('domaineName')) {
            $project->domaineName = $request->input('domaineName');
        }
        if ($request->has('repository')) {
            $project->repository = $request->input('repository');
        }
        if ($request->has('project_type')) {
            $project->project_type = $request->input('project_type');
        }
    
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/projects'), $imageName);
            $project->image_url = 'uploads/projects/' . $imageName;
        }
    
        if ($project->save()) {
            return response([
                'STATE' => ApiResponse::OK,
                'data' => new ProjectResource($project),
            ]);
        }
    
        return response(['STATE' => ApiResponse::ERROR]);
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
