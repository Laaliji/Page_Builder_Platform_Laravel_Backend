<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\ApiResponse;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request){
        return $this->projectService->addProject($request->validated());
    }

    public function update(Request $request, $id){
        $project = Project::find($id);
        
        if(!$project){
            return response(['STATE'=>ApiResponse::NOT_FOUND]);
        }

        if($request->only('title') === $request->all()){
            
            $project->title = $request->title;

            if($project->save()) return response([
                "STATE" => ApiResponse::OK,
            ]);

            return response(["STATE" => ApiResponse::ERROR]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'domaineName' => 'required|string|max:255',
            'repository' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ], [
            'title.required' => 'Le titre du projet est obligatoire',
            'description.required' => 'Veuillez fournir une description pour le projet.',
            'domaineName.required' => 'Le nom du domaine est obligatoire.',
            'repository.url' => 'L\'URL du dépôt doit être valide.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Seuls les formats JPEG, PNG et JPG sont autorisés pour l\'image.',
        ]);

        if($validator->fails()){
            return response([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors()
            ]);
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

    public function destroy(Request $request, $id){
        $project = Project::find($id);
        
        if (!$project) {
            return response(['id' => $id, 'STATE' => ApiResponse::NOT_FOUND]);
        }
        
        if ($request->has('title')) {

            if ($project->title == $request->title) { 
                if ($project->delete()) {
                    return response(["STATE" => ApiResponse::OK]);
                }
                return response(["STATE" => ApiResponse::ERROR]);
            }
            return response(["STATE" => ApiResponse::INVALID_DATA]);
        }
        
        if ($project->delete()) {
            return response(['STATE' => ApiResponse::OK]);
        }
        
        return response(['STATE' => ApiResponse::ERROR]);
    }


}
