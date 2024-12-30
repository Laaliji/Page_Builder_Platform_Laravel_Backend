<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    
    public function show($id){
        $userProfile = UserProfile::find($id);

        if(!$userProfile){
            return response(['STATE'=>ApiResponse::NOT_FOUND]);
        }

        return new UserProfileResource($userProfile);
    }


    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request,$id) {
        $userProfile = UserProfile::find($id);
        if(!$userProfile){
            return response(['STATE'=>ApiResponse::NOT_FOUND]);
        }else if($request->password){
            $user = $userProfile->user;
            if(!Hash::check($request->current_password, $user->password)){
                return response(['STATE'=>ApiResponse::INVALID_DATA]);
            }
            $user->password = Hash::make($request->password);
            if($user->save()){
                return response(['STATE'=>ApiResponse::OK]);
            }
        }else {
            
            $user = $userProfile->user;
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $user->save();
            
            if($request->hasFile('image')){
                $image = $request->file('image');
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('uploads/users'), $imageName);
                $userProfile->image = 'uploads/users/'.$imageName;
            }
    
            if($userProfile->save()){
                return response(['STATE'=>ApiResponse::OK]);
            }else{
                return response(['STATE'=>ApiResponse::ERROR]);
            }
        }
        
    }


    public function destroy(Request $request): RedirectResponse{
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
