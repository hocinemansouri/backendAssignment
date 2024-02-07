<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    function index()
    {
        return view('profile');
    }

    public function change_password(ChangePasswordRequest $request,AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
        if (Hash::check($request->old_password, $user->password)) {
            $this->userRepository->update($user,['password' => Hash::make($request->password)]);
            return response()->json([
                'message' => 'Password changed succesfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Old password does not matched',
            ], 400);
        }
    }

    public function update_profile(UpdateUserRequest $request,AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                $old_path = public_path() . 'upload/profile_images/' . $user->profile_photo;
                if (Storage::exists($old_path)) {
                    Storage::delete($old_path);
                }
            }
            $image_name = 'profile-image-' . time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('/upload/profile_images'), $image_name);
        } else {
            $image_name = $user->profile_photo;
        }
        $username = strtolower($request->surname . (substr($request->name, 0, 3)));
        $this->userRepository->update($user,[
            'name' => $request->name,
            'surname' => $request->surname,
            'username' => $username,
            'nickname' => $request->nickname,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'profile_photo' => $image_name,
            
        ]);
        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Profile updated succesfully',
                'user' => $user
            ], 200);
        } else {
            return view('/updateprofile',['isClicked'=>true]);
        }
    }
}
