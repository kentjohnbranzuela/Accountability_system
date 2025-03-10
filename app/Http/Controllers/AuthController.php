<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import User model

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerate session to prevent fixation
            session(['user_id' => Auth::id()]); // Ensure user_id is stored in session
            session()->save(); // Force session to save
    
            // If "Remember Me" is checked, store the email in a cookie
            if ($request->has('remember')) {
                Cookie::queue('email', $request->email, 43200); // Store for 30 days
            } else {
                Cookie::queue(Cookie::forget('email')); // Remove email if unchecked
            }
    
            return redirect()->intended('/dashboard')->with('success', 'Login successful.');
        }
    
        return back()->withErrors(['email' => 'Invalid login credentials.']);
    }
    public function accountInfo()
{
    $user = Auth::user(); // Get the currently authenticated user
    return view('account.info', compact('user')); // Pass the user data to the view
}
public function updatePassword(Request $request)
{
    $user = auth()->user();

    // Validate input
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:4|confirmed',
    ]);

    // Check if current password is correct
    if (!Hash::check($request->current_password, $user->password)) {
        return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    // Update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    // Log the password update
    \Log::info("User ID {$user->id} updated their password.", ['user_id' => $user->id]);

    return redirect()->route('account.info')->with('success', 'Password updated successfully!');
}

public function updateAccount(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
    ]);

    $user = Auth::user();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return redirect()->route('account.info')->with('success', 'Account details updated successfully.');
}

    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // If Remember Me is unchecked, remove the email cookie
    if (!Cookie::get('email')) {
        Cookie::queue(Cookie::forget('email'));
    }

    // Store logout message in session
    $request->session()->flash('success', 'You have been logged out.');

    // Debug session data after storing flash message
    $request->session()->flash('success', 'You have been logged out.');
return redirect('/login');

    return redirect('/login');
}

}


