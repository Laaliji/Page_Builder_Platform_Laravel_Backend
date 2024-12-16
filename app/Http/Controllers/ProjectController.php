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

    public function update(Request $request, $id){
        $project = Project::find($id);
        
        if(!$project){
            return response(['STATE'=>ApiResponse::NOT_FOUND]);
        }
    
        $project->title = $request->input('title');
        $project->desctiption = $request->input('description');
        $project->domaineName = $request->input('domaineName');
        $project->repository = $request->input('repository');
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/projects'), $imageName);
            $project->image_url = 'uploads/projects/'.$imageName;
        }

        if($project->save()){
            return response([
                'STATE' => ApiResponse::OK,
                'data' => $request->all()
            ]);
        }
    
        return response(['STATE'=>ApiResponse::ERROR]);
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
