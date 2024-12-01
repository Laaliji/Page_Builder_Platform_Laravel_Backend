<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectManagementService $projectService)
    {
        $this->projectService = $projectService;
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

    public function destroy($idP)
    {
        return $this->projectService->deleteProject($idP);
    }
}
