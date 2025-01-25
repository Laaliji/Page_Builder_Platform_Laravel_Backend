<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Template;

class TemplateController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'html_content' => 'nullable|string',
            'css_content' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
       
        $template = Template::create([
            'name' => $request->name,
            'html_content' => $request->html_content,
            'css_content' => $request->css_content,
            'description' => $request->description,
        ]);
        
        return response()->json(['message' => 'Template created successfully!', 'template' => $template]);
    }
}