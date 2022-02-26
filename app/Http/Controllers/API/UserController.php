<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Hasil;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ], [
                'password.required' => 'Kamu harus mengisi kata sandi',
                'email.required' => 'Kamu harus mengisi email',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ResponseFormatter::error([
                    'message' => $error
                ], 'Authentication Failed', 500);
            }

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Email atau password salah'
                ], 'Authentication Failed', 500);
            }
            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated', 200);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => 'required|min:8|confirmed',
            ], [
                'name.required' => 'Kamu harus mengisi nama',
                'email.required' => 'Kamu harus mengisi email',
                'email.email' => 'Format email tidak tepat',
                'email.unique' => 'Email sudah dipakai',
                'password.required' => 'Kata sandi harus diisi',
                'password.min' => 'Kata sandi minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi kata sandi tidak sama'
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ResponseFormatter::error([
                    'message' => $error
                ], 'Authentication Failed', 500);
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e,
            ], 'Authentication Failed', 500);
        }
    }

    public function updateProfile(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ], [
                'name.required' => 'Kamu harus mengisi nama',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ResponseFormatter::error([
                    'message' => $error
                ], 'Authentication Failed', 500);
            }

            $user = Auth::user();
            $user->name = $request->name;
            $user->save();
            return ResponseFormatter::success($user, 'Profile Updated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }

        return ResponseFormatter::success($user, 'Profile Updated');
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ], [
                'current_password.required' => 'Kata sandi lama harus diisi',
                'password.required' => 'Kata sandi baru harus diisi',
                'password.min' => 'Kata sandi baru minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi kata sandi baru tidak sama',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ResponseFormatter::error([
                    'message' => $error
                ], 'Authentication Failed', 500);
            }

            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password, [])) {
                return ResponseFormatter::error([
                    'message' => 'Kata sandi lama salah',
                ], 'Authentication Failed', 500);
            }
            $user->password = Hash::make($request->password);
            $user->save();

            return ResponseFormatter::success($user, 'Password Updated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function getRiwayatKonsultasi()
    {
        $hasil = Hasil::where('id_user', Auth::id())->with(['penyakit'])->orderBy('created_at', 'desc')->get();
        return ResponseFormatter::success($hasil, 'Successfully get consultation history');
    }
}
