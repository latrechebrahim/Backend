<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use App\Models\Doctor;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (empty($email) || empty($password)) {
            $response = ['status' => 400, 'message' => 'Email and password are required.'];
            return response()->json($response);
        }
        $user = User::where('email', $email)->first();

        if (!$user) {
            $response = ['status' => 401, 'message' => 'No account found with this email. Please try again or register for a new account.'];
            return response()->json($response);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $response = ['status' => 200, 'user' => $user, 'message' => 'Login Successfully! Welcome to our platform'];
            return response()->json($response);
        } else {
            $response = ['status' => 402, 'message' => 'wrong password. Please try again.'];
            return response()->json($response);
        }
    }

    public function login_phone(Request $request)
    {

        $phoneNumber = $request->input('phonenumber');
        $password = $request->input('password');

        if (empty($phoneNumber) || empty($password)) {
            $response = ['status' => 400, 'message' => 'Phone number and password are required.'];
            return response()->json($response, 400);
        }

        $user = User::where('phonenumber', $phoneNumber)->first();

        if (!$user) {

            $response = ['status' => 404, 'message' => 'No account found with this phone number. Please try again or register for a new account.'];
            return response()->json($response, 404);
        }

        if (Hash::check($password, $user->password)) {

            $response = ['status' => 200, 'user' => $user, 'message' => 'Login Successfully! Welcome to our platform'];
            return response()->json($response);
        } else {

            $response = ['status' => 401, 'message' => 'Incorrect password. Please try again.'];
            return response()->json($response, 401);
        }
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $verificationCode = rand(1000, 9999);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $verificationCode]
        );

        // Send the verification code via email
        Mail::to($email)->send(new VerificationCodeMail($verificationCode));

        //return response()->json(['status' => 200, 'message' => 'Verification code sent successfully']);
        return view('verification_code', ['verificationCode' => $verificationCode]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $email = $request->input('email');
        $verificationCode = $request->input('token');
        $newPassword = $request->input('new_password');

        $savedVerificationCode = DB::table('password_resets')->where('email', $email)->value('token');

        if (!$savedVerificationCode || $savedVerificationCode != $verificationCode) {
            return response()->json(['status' => 400, 'message' => 'Invalid verification code']);
        }

        // Update the user's password
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User not found']);
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        // Remove the verification code from the database or cache after password change
        DB::table('password_resets')->where('email', $email)->delete();

        return response()->json(['status' => 200, 'message' => 'Password changed successfully']);
    }




    public function LoginDoctors(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (empty($email) || empty($password)) {
            $response = ['status' => 400, 'message' => 'Email and password are required.'];
            return response()->json($response);
        }
        $user = Doctor::where('email', $email)->first();

        if (!$user) {
            $response = ['status' => 401, 'message' => 'No account found with this email. Please please verify your email...'];
            return response()->json($response);
        }

        if (Hash::check($password, $user->password)) {
            $response = ['status' => 200, 'user' => $user, 'message' => 'Login Successfully! Welcome to our platform'];
            return response()->json($response);
        } else {
            $response = ['status' => 402, 'message' => 'wrong password. Please try again.'];
            return response()->json($response);
        }
    }
}
