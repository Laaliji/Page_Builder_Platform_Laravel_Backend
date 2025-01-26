<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

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
        return Cache::remember("Project_{$id}", now()->addMinutes(30), function () use ($project) {
            return new ProjectResource($project);
        });
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
            'domaineName' => 'string|max:255',
            'repository' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'title.required' => 'Le titre du projet est obligatoire.',
            'description.required' => 'Veuillez fournir une description pour le projet.',
            'domaineName.required' => 'Le nom du domaine est obligatoire.',
            'repository.url' => 'L\'URL du dépôt doit être valide.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Seuls les formats JPEG, PNG et JPG sont autorisés.',
            'image.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
        ]);
    
        if ($validator->fails()) {
            return response([
                'STATE' => ApiResponse::INVALID_DATA,
                'ERRORS' => $validator->errors(),
            ]);
        }
    
        $project = new Project();
        $project->title = $request->title;
        $project->desctiption = $request->description;
        $project->domaineName = $request->domaineName;
        $project->repository = $request->repository;
        $project->user_id = $request->user_id;
    
        if(!request()->has('image')) {
            $project->image_url = "";
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
    

    public function getProjectsByUser($id){
        $user = User::find($id);
        $PorjectByUser_CacheKey = "PorjectsByUser_{$id}";
        if(!$user){
            return response(['message'=>'user NotFound','STATE' => ApiResponse::NOT_FOUND]);
        }
        if(Cache::has($PorjectByUser_CacheKey)){
            return Cache::get($PorjectByUser_CacheKey);
        }

        Cache::put($PorjectByUser_CacheKey, ProjectResource::collection($user->projects), now()->addMinutes(50));
        return ProjectResource::collection($user->projects);
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
            Cache::forget("Project_{$id}");
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

        Cache::forget("Project_{$id}");
        
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
