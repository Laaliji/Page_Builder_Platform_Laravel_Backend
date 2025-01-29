<?php

namespace App\Http\Controllers;

use App\Http\Resources\StyleResource;
use App\Models\Style;
use Illuminate\Http\Request;
use App\Enums\ApiResponse;

class StyleController extends Controller
{
    /**
     * Display a listing of all styles
     */
    public function index()
    {
        return StyleResource::collection(
            Style::query()->orderBy('id')->get()
        );
    }

    /**
     * Display the specified style
     */
    public function show($id)
    {
        $style = Style::find($id);
        if (!$style) {
            return response(['STATE' => ApiResponse::NOT_FOUND]);
        }
        return new StyleResource($style);
    }

    /**
     * Store a newly created style
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:50',
            'secondary_color' => 'nullable|string|max:50',
            'tertiary_color' => 'nullable|string|max:50',
            'quaternary_color' => 'nullable|string|max:50',
            'background_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50',
            'is_dark_mode' => 'nullable|boolean'
        ]);

        $style = new Style($validatedData);

        if ($style->save()) {
            return response([
                'STATE' => ApiResponse::OK,
                'data' => new StyleResource($style)
            ]);
        }

        return response(['STATE' => ApiResponse::ERROR]);
    }

    /**
     * Update the specified style
     */
    public function update(Request $request, $id)
    {
        $style = Style::find($id);

        if (!$style) {
            return response(['STATE' => ApiResponse::NOT_FOUND]);
        }

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:50',
            'secondary_color' => 'nullable|string|max:50',
            'tertiary_color' => 'nullable|string|max:50',
            'quaternary_color' => 'nullable|string|max:50',
            'background_color' => 'nullable|string|max:50',
            'text_color' => 'nullable|string|max:50',
            'is_dark_mode' => 'nullable|boolean'
        ]);

        if ($style->update($validatedData)) {
            return response([
                'STATE' => ApiResponse::OK,
                'data' => new StyleResource($style)
            ]);
        }

        return response(['STATE' => ApiResponse::ERROR]);
    }

    /**
     * Remove the specified style
     */
    public function destroy($id)
    {
        $style = Style::find($id);
        if (!$style) {
            return response(['message' => 'Style NotFound', 'STATE' => ApiResponse::NOT_FOUND]);
        }

        // Check if style is associated with any projects
        if ($style->projects()->count() > 0) {
            return response([
                'message' => 'Cannot delete style as it is associated with projects',
                'STATE' => ApiResponse::ERROR
            ]);
        }

        if ($style->delete()) {
            return response(['STATE' => ApiResponse::OK]);
        }

        return response(['STATE' => ApiResponse::ERROR]);
    }

    /**
     * Get all projects using a specific style
     */
    public function getProjectsByStyle($id)
    {
        $style = Style::find($id);
        if (!$style) {
            return response(['message' => 'Style NotFound', 'STATE' => ApiResponse::NOT_FOUND]);
        }

        return response([
            'STATE' => ApiResponse::OK,
            'data' => $style->projects
        ]);
    }
}