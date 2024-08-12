<?php

namespace App\Http\Controllers;

use App\Mail\helloMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class Auth extends Controller
{
        public function create()
        {
            return Inertia::render('User/register');
        }
    
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'type'=>'required',
                'password' => 'required|string|min:6',
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'IsAdmin'=>$request->type,
                'password' => Hash::make($request->password)
            ]);
    
            //event(new Registered($user));
    
            return redirect()->route('users')->with('success', 'User registered successfully!');
        }
    
        public function login()
        {
            return Inertia::render('User/login');
        }
    
        public function auth(Request $request)
        {
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
    
            if (auth()->attempt($request->only('email', 'password'), true)) {
                $request->session()->regenerate();
                return redirect()->route('home');
            } else {
                return redirect()->route('login')->with([
                    'failed' => 'Email or password not correct',
                ]);
            }
        }
    
        public function logout(Request $request)
        {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        }
    
        public function index()
        {
            $users = User::paginate(5);
            return Inertia::render('User/users', [
                'users' => $users,
            ]);
        }
    
        public function destroy(User $user)
        {
            $user->delete();
            return redirect()->route('users')->with([
                'success' => 'User deleted successfully!',
            ]);
        }
    
        public function show($id)
        {
            $user = User::findOrFail($id);
            return Inertia::render('User/profile', [
                'user' => $user,
            ]);
        }
    
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'nullable|string|min:6',
            ]);
    
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
    
            return redirect()->route('home')->with('success', 'Profile updated successfully!');
        }
    
        public function forgot_password()
        {
            return Inertia::render('User/VerifyEmail');
        }
    
        public function verify_email(Request $request)
         {
            $request->validate([
                'email' => 'required|string|email|max:255',
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            if ($user) {
                $fullname = $user->name;
                $token = md5(uniqid()) . $email . sha1($email);

                // Stocker le jeton dans la table users
                $user->remember_token = $token;
                $user->save();

                // Envoyer l'e-mail de réinitialisation de mot de passe
                Mail::to($email)->send(new helloMail($fullname, $token,$email));

                return redirect()->route('login')->with('success', 'A password reset link has been sent to your email address.');
            } else {
                return back()->with('failed', 'This email does not exist.');
            }
        }

        public function showResetForm(Request $request, $token = null)
        {   $email = $request->query('email');
            return Inertia::render('User/ResetPassword', [
                'token' => $token,
                'email' => $email,
            ]);
        }

        public function reset(Request $request)
        {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ]);

            $user = User::where('email', $request->email)->where('remember_token', $request->token)->first();

            if ($user) {
                $user->password = Hash::make($request->password);
                $user->remember_token = null; // Invalider le token après utilisation
                $user->save();

                return redirect()->route('login')->with('success', 'Your password has been reset!');
            } else {
                return back()->withErrors(['email' => 'Invalid token or email.']);
            }
        }
            
}
    