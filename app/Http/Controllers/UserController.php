<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class UserController extends Controller {
    public function showRegistrationForm() {
        return view('register');
    }

    public function showDashboard() {
        return view('customer/dashboard');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'cp_number' => 'required|numeric|digits:11',
            'address' => 'required|string',
        ],[
            'cp_number.digits' => 'The phone number must be 11 digits.',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'cp_number' => $request->input('cp_number'),
            'address' => $request->input('address'),
        ]);

        return redirect('/')->with('success', 'Registration successful!');
    }

    public function showUsers() {
        $users = User::all();

        return view('admin.viewUsers', compact('users'));
    }

    public function updateUserStatus($userId) {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->status = 0;
        $user->save();
        return response()->json(['message' => 'User status updated successfully']);
    }

    public function updateUserType($userId) {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->type = 'admin';
        $user->save();
        return response()->json(['message' => 'User type updated successfully']);
    }

    public function modifyUser($userId) {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->name = request('newName');
        $user->email = request('newEmail');
        $user->password = Hash::make(request('newPassword'));
        $user->cp_number = request('newCpNumber');
        $user->address = request('newAddress');
        $user->save();

        return response()->json(['message' => 'User modified successfully']);
    }

    public function getUser($userId) {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json(['user' => $user]);
    }

    public function showPersonalInfo() {
        $user = Auth::user();
        return view('customer/personalInfo', compact('user'));
    }

    public function showUserID() {
        $user = Auth::user();
        return view('customer/dashboard', compact('user'));
    }

    public function updatePersonalInfo(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cp_number' => 'required|numeric|digits:11',
            'address' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cp_number' => $request->input('cp_number'),
            'address' => $request->input('address'),
        ]);

        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }

    public function getPassword() {
        return view('/customer/newPassword');
    }

    public function changePassword(Request $request) {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return redirect()->back()->with("error","Your current password does not match with the password.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            return redirect()->back()->with("error","New Password cannot be the same as your current password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password successfully changed!");
    }
    
}
