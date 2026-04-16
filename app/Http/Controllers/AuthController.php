<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordReset;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userType' => 'required|in:teacher,student',
            'firstName' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/u', 'max:255'],
            'lastName' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/u', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email', 'unique:teachers,email'],
            'password' => 'required|min:6',
            'gender' => 'required|in:male,female',
            'hobbies' => 'required|array|min:1',
            'hobbies.*' => 'in:reading,sports,music',
            'birthdate' => 'required|date|before:today',
        ], [
            'firstName.required' => 'First name is required',
            'firstName.regex' => 'First name must contain only letters',
            'lastName.required' => 'Last name is required',
            'lastName.regex' => 'Last name must contain only letters',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email is already in use',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender must be male or female',
            'hobbies.required' => 'At least one hobby must be selected',
            'hobbies.*.in' => 'Invalid hobby selected',
            'birthdate.required' => 'Birthdate is required',
            'birthdate.before' => 'Birthdate cannot be in the future',
            'userType.required' => 'User type is required',
            'userType.in' => 'User type must be teacher or student',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Adjust to use correct database field names
        $data = [
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'password' => Hash::make($request->password),
            'hobbies' => implode(',', $request->hobbies),
        ];

        $model = $request->userType === 'teacher' ? Teacher::class : Student::class;
        $user = $model::create($data);

        session(['user_type' => $request->userType, 'user_id' => $user->id, 'user_name' => $user->first_name]);

        return redirect()->route('login')->with('success', 'Registration successful');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userType' => 'required|in:teacher,student',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'userType.required' => 'User type is required',
            'userType.in' => 'User type must be teacher or student',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $model = $request->userType === 'teacher' ? Teacher::class : Student::class;
        $user = $model::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['user_type' => $request->userType, 'user_id' => $user->id, 'user_name' => $user->first_name]);
            
            if ($request->userType === 'student') {
                return redirect()->route('student.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Incorrect email or password'])->withInput();
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userType' => 'required|in:teacher,student',
            'email' => 'required|email',
        ], [
            'userType.required' => 'User type is required',
            'userType.in' => 'User type must be teacher or student',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $model = $request->userType === 'teacher' ? Teacher::class : Student::class;
        $user = $model::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email is not registered'])->withInput();
        }

        // Generate a 6-digit verification code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::uuid()->toString();

        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'user_type' => $request->userType,
            'verification_code' => $verificationCode,
            'created_at' => now(),
        ]);

        // Send reset email with token and verification code
        Mail::to($request->email)->send(new ResetPasswordMail($token, $verificationCode, $request->userType));

        // Simple SMTP test (you can remove it after testing)
        try {
            Mail::raw('This is a test email to check SMTP.', function ($message) use ($request) {
                $message->to($request->email)->subject('Test SMTP Connection');
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }

        // Redirect to reset password page
        return redirect()->route('password.reset', ['token' => $token]);
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userType' => 'required|in:teacher,student',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
            'verification_code' => 'required|digits:6',
        ], [
            'userType.required' => 'User type is required',
            'userType.in' => 'User type must be teacher or student',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'token.required' => 'Token is required',
            'verification_code.required' => 'Verification code is required',
            'verification_code.digits' => 'Verification code must be 6 digits',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->token)
            ->where('user_type', $request->userType)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$passwordReset) {
            return redirect()->back()->withErrors(['email' => 'The token or verification code is invalid or expired'])->withInput();
        }

        $model = $request->userType === 'teacher' ? Teacher::class : Student::class;
        $user = $model::where('email', $request->email)->first();

        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            // Delete reset record
            PasswordReset::where('email', $request->email)
                ->where('token', $request->token)
                ->where('user_type', $request->userType)
                ->where('verification_code', $request->verification_code)
                ->delete();
            return redirect()->route('login')->with('success', 'Password has been reset successfully');
        }

        return redirect()->back()->withErrors(['email' => 'Email is not registered'])->withInput();
    }
}
