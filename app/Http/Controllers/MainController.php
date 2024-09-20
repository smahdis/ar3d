<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCode;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function download($code) {
        $project_code = ProjectCode::where('code', $code)->first();
        if(isset($project_code)) {
            $project = Project::where('id', $project_code->project_id)->where('status', 1)->get();
            $project->load('attachment');
            //On live server, I noticed some /1 is appended after the domain name, which i don't know where it comes from. This will remove it if it exits.
            $url = $project[0]->attachments()->latest()->first() ? preg_replace('#^' . preg_quote('/1') . '#', '', $project[0]->attachments()->latest()->first()->relative_url) : "";
            return response(['error'=>false,'url'=>$url],200);
        }

        return response(['error'=>true,'message'=>"The specified code is invalid"],404);

    }
}
