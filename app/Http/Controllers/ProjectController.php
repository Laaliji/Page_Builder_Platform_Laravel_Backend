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

    // public function __construct(ProjectManagementService $projectService)
    // {
    //     $this->projectService = $projectService;
    // }

    public function index(){
        return ProjectResource::collection(
            Project::query()->orderBy('idP')->get()
        );
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
        ]);
    
        $project = new Project();
        $project->title = $validatedData['projectName'];
        $project->description = $validatedData['projectDescription'] ?? null;
        $project->domaineName = $validatedData['websiteTitle'] ?? null;
        $project->repository = $validatedData['repoUrl'] ?? null;
        $project->user_id = $validatedData['user_id'];
    
        // Check if the image is provided, if not, set image_url to an empty string
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/projects'), $imageName);
            $project->image_url = 'uploads/projects/' . $imageName;
        } else {
            // Set image_url to an empty string if no image is uploaded
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

    $project->title = $request->input('title');
    $project->description = $request->input('description');
    $project->domaineName = $request->input('domaineName');
    $project->repository = $request->input('repository');

    // If an image is uploaded, update the image_url; otherwise, keep it null or empty
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/projects'), $imageName);
        $project->image_url = 'uploads/projects/' . $imageName;
    } else {
        // Set image_url to null or empty string if no new image is uploaded
        $project->image_url = null;  // Or you could use '' if you prefer
    }

    if ($project->save()) {
        return response([
            'STATE' => ApiResponse::OK,
            'data' => $request->all(),
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
