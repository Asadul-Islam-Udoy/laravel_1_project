<?php
namespace App\Http\Controllers;
use  App\Models\GroupVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
class GroupVideoController extends Controller
{
    public function create_group_name(Request $request){
        $validator = Validator::make($request->all(),[
         'name'=>'required',
         'profession'=>'required',
         'user_id'=>'required'
        ]);
        if($validator->fails()){
            return response([
                'subject'=>false,
                'message'=>$validator->errors()
            ],400);  
        }
        $groupVideoFile = GroupVideo::create([
            'name'=> $request->name,
            'profession'=>$request->profession,
            'user_id'=>Auth::id()
        ]);
        $groups = GroupVideo::where('user_id',Auth::id());
        if($groupVideoFile){
            return response([
                'subject'=>true,
                'message'=>'groupname create successfully',
                'groups'=>$groups
            ],200);
        }
        else{
            $errormessage=array(
                'message'=>['groupname create falis!']
            );
            return response([
                'subject'=>false,
                'message'=>(object)$errormessage
            ],400);
        }
    }
 public function get__groups_name(){
    $groups = GroupVideo::where('user_id',Auth::id())->get();
    if($groups){
        return response([
            'subject'=>true,
            'message'=>'groupname getting successfully',
            'groups'=>$groups
        ],200);
    }
 }



}




//  public function update_video(Request $request, $id){
//     $video = Video::find($id);
//     $videoFile = GroupVieoFile::where('video_id',$id)->get();
//     $video->title = $request->title;
//     $video->description = $request->description;
//     if(count($request->hasFile('video'))>0){
//       foreach($videoFile as $item){
//       if(File::existis('videos/'.$item->videos)){
//         File::delete('videos/'.$item->videos);
//       };
//       $videof = GroupVieoFile::find($item->id);
//       if(count($request->hasFile('video'))>0){
//       foreach($request->file('video') as $file){
//         $filename = time().'.'.$file->getClientOriginalExtension();
//         $file->move(public_path('/videos'),$filename);
//         $videof->videos = $filename; 
//         $videof->save(); 
//       }
      
//       }
//       else{
//         $file = $request->file('video');           }       
//         $filename = time().'.'.$file->getClientOriginalExtension();
//         $file->move(public_path('/videos'),$filename);
//         $videof->videos= $filename;
//         $videof->save();
//       }
//       }
//       $video->save();
//       $videos = Video::all();
//       return response([
//           'success'=>true,
//            'message'=>'update successfully',
//            'videos'=>$videos
//       ]);
//     }