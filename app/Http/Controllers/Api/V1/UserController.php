<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\User\CreateUserRequest;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showUserProfile($userId)
    {
        $user = $this->userRepository->getById($userId);

        // ... controller logic

        return response()->json(['user' => $user]);
    }

    public function updateUserProfile($userId, Request $request)
    {
        $user = $this->userRepository->getById($userId);

        // ... controller logic

        $updatedUser = $this->userRepository->update($user, $request->all());

        // ... more logic

        
        return response()->json(['user' => $updatedUser]);
    }
    public function register(CreateUserRequest $request)
    {

        $fname = $request->name;
        $lname = $request->surname;
        $username = strtolower($lname . (substr($fname, 0, 3)));
        $user = $this->userRepository->create([
            //'name' => $fname,
            //'surname' => $lname,
            //'username' => $username,
            //'nickname' => $request->nickname,
            //'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            //'phone' => $request->phone,
            //'address' => $request->address,
            //'city' => $request->city,
            //'state' => $request->state,
            //'zip_code' => $request->zip_code,
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;
        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Registration succesful',
                'token' => $token,
                'user' => $user
            ], 200);
        } else {
            $request->session()->put('user', $user);
            return redirect()
                ->intended('/posts');
        }
    }

    public function login(AuthLoginRequest $request)
    {
        $user = $this->userRepository->getByEmail($request->email);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth-token')->plainTextToken;
                if ($request->is('api/*')) {
                    return response()->json([
                        'message' => 'You\'re logged in succesfully',
                        'token' => $token,
                        'user' => $user
                    ], 200);
                } else {
                    $request->session()->put('user', $user);
                    return redirect()
                        ->intended('/posts');
                }
            } else {
                return response()->json([
                    'message' => 'Incorrect credentials'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Incorrect credentials'
            ], 400);
        }
    }

    public function user(Request $request)
    {
        return response()->json([
            'message' => 'User fetched succesfully',
            'data' => $request->user(),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->is('api/*')) {
            auth()->logout();
            return response()->json([
                'message' => 'User logged out succesfully',
            ], 200);
        } else {
            $request->session()->flush();
            $request->session()->forget('user', $request->user());
            return redirect('/posts');
        }
    }
}
