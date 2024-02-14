<?php

namespace App\Http\Controllers;
use App\Models\Video;
use App\Models\GroupVieoFile;
use Illuminate\Support\Facades\Validator;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Auth;
class VideoController extends Controller
{
    public function get_all_video(Request $request){
        $q = $request->query('keyword');
        $videos = Video::orWhere('title','like',"%{$q}%")->orWhere('description', 'like',"%{$q}%")->with('groupsfile')->paginate(10);
        return response([
            'success'=>true,
            'message'=>'getting successfully',
            'videos'=>$videos
        ],200);
    }



   public function get_video_admin(){
    $videos = Video::all();
    return response([
        'success'=>true,
        'message'=>'getting successfully',
        'videos'=>$videos
    ],200);
   }



    public function create_video(Request $request){
     $videos = new Video;
     $vid=Video::create([
        'title'=> $request->title,
        'user_id'=> Auth::id(),
        'description'=> $request->description,
        'groupname'=> 'null'
     ]);
     if($request->has('video')){
        $file = $request->file('video');
        $filename = time() . "." . $file->getClientOriginalExtension();
        $file->move(public_path('/videos'), $filename);
        GroupVieoFile::create([
        'videos' => $filename,
        'user_id'=> $vid->user_id,
        'video_id'=> $vid->id,
     ]);
    }
    else{
        return response([
            'success' => false,
            'message' => 'create fail',
         ],400);
    };
    $videos = Video::where('user_id',Auth::id())->with('groupsfile')->get();
     return response([
        'success' => true,
        'message' => 'create successfully',
        'videos' => $videos
     ],200);
    
}

public function update_video(Request $request, $id){
        $video = Video::find($id);
        $videoFile = GroupVieoFile::where('video_id',$id)->first();
        $video->title = $request->title;
        $video->description = $request->description;
         if(File::existis('videos/'.$videoFile->videos)){
            File::delete('videos/'.$videoFile->videos);
          };
        if($request->has('video')){
            $file = $request->file('video');
            if(!is_null($file)) {
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/videos'),$filename);
            $videoFile->videos= $filename;
            }
          };
          $video->save();
          $videoFile->save();
          $videos = Video::where('user_id',Auth::id())->with('groupsfile')->get();
          return response([
              'success'=>true,
               'message'=>'update successfully',
               'videos'=>$videos
          ]);
        }
 public function delete_video($id){
    $video = Video::find($id);
    $videosfile = GroupVieoFile::where('video_id',$id)->first();
    if(File::exists('videos/'.$videosfile->videos)){
        File::delete('videos/'.$videosfile->videos);
    };
     $video->delete();
     $videosfile->delete();
     $videos = Video::where('user_id',Auth::id())->with('groupsfile')->get();
     return response([
         'success'=>true,
          'message'=>'delete successfully',
          'videos'=>$videos
     ]);
 }


 public function group_video_create(Request $request){
   $vides = Video::create([
       'user_id'=>Auth::id(),
       'title'=>$request->title,
       'groupname'=>$request->groupname,
       'description'=>$request->description
   ]) ;
   if($vides)  {
     if($request->has('video')){
        $file = $request->file('video');
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('/videos'),$filename);
        GroupVieoFile::create([
           'user_id'=>Auth::id(),
           'video_id'=>$vides->id ,
           'group_id'=>$request->group_id,
           'videos'=>$filename
        ]);
     }
     return response([
        'success'=>true,
        'message'=>'group videos create successfully!'
     ],200);
   } 
   else{
    return response([
        'success'=>false,
        'message'=>'group videos create fails!'
     ],400);
   } 
 }
}


