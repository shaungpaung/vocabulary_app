<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::all();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //\
        $validated = $request->validate([
            'user_name' => 'required|string',
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => 'required'
        ]);
        $validated["password"] = Hash::make($validated["password"]);
        $user = User::create($validated);
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Could not find this id: ' . $id], 422);
        }
        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Could not find this id: ' . $id], 422);
        }
        $validated = $request->validate([
            'user_name' => 'required|string',
            'email' => ['required', Rule::unique('users', 'email')->ignore($id, 'id')],
        ]);
        if ($user) {
            if ($request->has("password")) {
                $request->validate([
                    'password' => 'required'
                ]);
                $validated["password"] = Hash::make($request->password);
            }
            $user->update($validated);
            return response()->json(User::find($user->id));
        } else {
            return response()->json(['message' => "Could not find this id: " . $id], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Could not find this id: ' . $id], 422);
        }
        $user->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('user_name', $validated["user_name"])->first();
        if (!$user) {
            return response()->json(['message' => "Username is not found."], 422);
        }
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => "Password is incorrect."], 422);
        }
        $token = $user->createToken($validated['user_name'])->plainTextToken;
        return response()->json([
            'user' => User::find($user->id),
            'token' => $token,
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => "Successfully logged out."]);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:App\Models\User,id',
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        $user = User::find($validated['user_id']);
        if (!Hash::check($validated['old_password'], $user->password)) {
            return response()->json(['message' => "Old password is invalid."], 422);
        }
        $user->update([
            'password' => Hash::make($validated["new_password"])
        ]);
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Successfully changed password.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:App\Models\User,id'
        ]);
        $user = User::find($validated['user_id']);
        $user->update([
            'password' => Hash::make('123456')
        ]);
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Successfully reset password.'
        ]);
    }
}