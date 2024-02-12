<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\PermissionUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class UserController extends Controller
{

    public function register_method(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email' => 'required|unique:users|max:255',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        $permission=PermissionUser::create([
            'name'=>'user',
            'user_id'=>$user->id
        ]);

        if($user){
            return $this->veryfiemail($request->email);
        };
    }
     public function veryfiemail($email){
        $user = User::where('email',$email)->first();
        $random = Str::random(40);
        $doman = URL::to('/api/verify-email');
        $url = $doman."/".$random;
        $data['url'] = $url;
        $data['email'] = $email;
        $data['title'] = 'Email Verify';
        $data['body'] = 'if you verify email please click this link';
        Mail::send('verifyEmail',['data'=>$data],function($message) use ($data){
         $message->to($data['email'])->subject($data['title']);
        });
        $user->remember_token = $random;
        $user->save();
        return response([
            'success'=>true,
             'message'=>'send link your email address',
        ]);
     }
    public function verify_method($token){
         $user = User::where('remember_token',$token)->first();
         $time = Carbon::now()->format('Y-m-d H:i:s');
         $user->email_verified_at = $time;
         $user->remember_token = '';
         $user->is_verified = 1;
         $user->save();
         return response([
            'success'=>true,
             'message'=>'email verified successfully!',
        ],200);
    }
    public function login_method(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'error'=>'validated_error',
                'message'=>$validator->errors(),
            ],400);
        };
        if(!$token = JWTAuth::attempt($validator->validated())){
            $studentMarks = array( 
                "error"=>['Unauthrization User'],  
            ); 
            return response([
                'success'=>false,
                'message'=>(object)$studentMarks,
            ],401);
        };
        return $this->responseWithToken($token);
    }
    protected function responseWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 100,
            'users'=>auth()->user()

        ]);
    }

    public function logout_method(Request $request){
        try {
           
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ],400);
        }
        catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, invalid token'
            ],404);
        }
        catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, invalid token time'
            ],404);
        };
 }



public function get_all_users(){
    $users = User::where('id', '!=' ,auth()->user()->id)->with('permission')->get();
    return response()->json([
        'success'=>true,
        'message'=>'getting all users successfully',
        'users'=>$users,
    ]);
}

public function delete_user($id){
    $users = User::find($id)->delete();
    $users = User::where('id', '!=' ,auth()->user()->id)->with('permission')->get();
    return response()->json([
        'success'=>true,
        'message'=>'user delete successfully',
        'users'=>$users,
    ]);
}

public function permission_user(Request $request,$id){
  $user = PermissionUser::where('user_id',$id)->first();
  $user->user_create = $request->permissionCreate;
  $user->user_update = $request->permissionUpdate;
  $user->user_delete = $request->permissionDelete;
  $user->user_get = $request->permissionGet;
  $user->name = $request->name;
  $user->save();
  $users = User::where('id', '!=' ,auth()->user()->id)->with('permission')->get();
  return response()->json([
    'success'=>true,
    'message'=>'create successfully',
    'users'=>$users
  ]);
}

public function role_permision(Request $request,$id){
    $user = User::find($id);
    if(!$user){
       return response()->json([
           'success'=>false,
            'message'=>'user is unvalid'
       ]);
    };
   $user->role = $request->userRole;
   $user->save();
   $users = User::where('id', '!=' ,auth()->user()->id)->with('permission')->get();
   return response()->json([
       'success'=>true,
        'message'=>'user role update successfully!'
   ]);
   }
}


    

