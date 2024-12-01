<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function addComponent(Request $request, Page $page)
    {
        // Validation logic here
        return $page->components()->create($request->validated());
    }

    public function removeComponent(Page $page, Component $component)
    {
        return $component->delete();
    }

    public function updateComponent(Request $request, Component $component)
    {
        // Validation logic here
        return $component->update($request->validated());
    }
}
