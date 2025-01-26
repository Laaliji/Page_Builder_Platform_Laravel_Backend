<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller {
  
    public function showPage($id){
        $page = Page::where('id', $id)->first();

        if($page){
            return response([
                "STATE" => ApiResponse::OK,
                "DATA" => $page,
            ]);
        }
        
        return response([
            "STATE" => ApiResponse::ERROR,
        ]);
        
    }

    public function show($id) {
        $CachedPages = Cache::remember("Pages_{$id}",now()->addMinutes(30), function () use ($id) {
            return Page::where('project_id', $id)->get();
        });

        return response([
            "STATE" => ApiResponse::OK,
            "DATA" => $CachedPages,
        ]);
    }

    public function store(Request $request) {
        $page = new Page();
        $page->id = $request->id;
        $page->title = $request->title;
        $page->html_page_title = $request->html_page_title;
        $page->html_content = $request->html_content;
        $page->css_content = $request->css_content;
        $page->project_id = $request->project_id;

        if($page->save()){
            Cache::forget("Pages_{$page->project_id}");
            return response([
                "STATE" => ApiResponse::OK,
            ]);
        }else{
            return response([
                "STATE" => ApiResponse::ERROR,
            ]);
        }
    }

    public function updatePageMetaData(Request $request, $id) {
        $page = Page::find($id);
        $page->title = $request->title;
        $page->html_page_title = $request->html_page_title;
        if($page->save()){
            Cache::forget("Pages_{$page->project_id}");
            return response([
                "STATE" => ApiResponse::OK,
            ]);
        }else{
            return response([
                "STATE" => ApiResponse::ERROR,
            ]);
        }
    }
    public function update(Request $request, $id) {
        try{
            $page = Page::find($id);
            $page->html_content = $request->html_content;
            $page->css_content  = $request->css_content;
            if($page->save()){
                Cache::forget("Pages_{$page->project_id}");
                return response([
                    "STATE" => ApiResponse::OK,
                ]);
            }else{
                return response([
                    "STATE" => ApiResponse::ERROR,
                ]);
            }
        }catch(\Exception $e){
            return response([
                "STATE" => ApiResponse::ERROR,
                "MESSAGE" => $e->getMessage(),
            ]);
        }
    }


    public function ExistePages($id){
        $pages = Page::where('project_id', $id)->get();
        return response([
            "EXISTE" => $pages->count() > 0,
        ]);
    }

    public function destroy($id) {
        $page = Page::find($id);
        if($page->delete()){
            Cache::forget("Pages_{$page->project_id}");
            return response([
                "STATE" => ApiResponse::OK,
            ]);
        }else{
            return response([
                "STATE" => ApiResponse::ERROR,
            ]);
        }
    }
}
