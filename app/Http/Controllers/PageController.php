<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller {
  
    public function show($id) {
        $pages = Page::where('project_id', $id)->get();

        return response([
            "STATE" => ApiResponse::OK,
            "DATA" => $pages,
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
