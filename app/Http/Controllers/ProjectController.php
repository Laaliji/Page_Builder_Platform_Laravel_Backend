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

    public function getProjectsByUser($id){
        $user = User::find($id);
        if(!$user){
            return response(['message'=>'user NotFound','STATE' => ApiResponse::NOT_FOUND]);
        }

        return ProjectResource::collection($user->projects);
    }

    public function store(Request $request)
    {
        // Validation logic here
        return $this->projectService->addProject($request->validated());
    }

    public function update(Request $request, $idP)
    {
        // Validation logic here
        return $this->projectService->updateProject($idP, $request->validated());
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
