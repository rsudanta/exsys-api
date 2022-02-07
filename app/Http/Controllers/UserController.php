<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Vote;
use App\Models\Pertanyaan;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);
        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|required|min:8|confirmed',
            'role' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'password.min' => 'Kata sandi minimal 8 karakter',
            'password.required' => 'Kata sandi harus diisi',
            'password.confirmed' => 'Konfirmasi Kata Sandi tidak sama',
            'email.required' => 'Kamu harus mengisi email',
            'email.email' => 'Format email tidak tepat',
            'email.unique' => 'Email sudah dipakai',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'item' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $data = $request->all();
        if ($user->email == $request->email) {
            $request->validate([
                'email' => 'required|string|email|max:255',
            ], [
                'email.required' => 'Kamu harus mengisi email',
                'email.email' => 'Format email tidak tepat',
                'email.unique' => 'Email sudah dipakai',
            ]);
        }

        if ($user->email != $request->email) {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
            ], [
                'email.required' => 'Kamu harus mengisi email',
                'email.email' => 'Format email tidak tepat',
                'email.unique' => 'Email sudah dipakai',
            ]);
        }
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Kamu harus mengisi nama',
        ]);
        if ($request->input('password') == null) {
            $data['password'] = $user->password;
        }

        if ($request->input('password') != null) {
            $request->validate([
                'password' => 'required|min:8|confirmed',
            ], [
                'password.min' => 'Kata sandi minimal 8 karakter',
                'password.required' => 'Kata sandi harus diisi',
                'password.confirmed' => 'Konfirmasi Kata Sandi tidak sama'
            ]);
            $data['password'] = Hash::make($request->password);
        }
        $user['role'] = $request->role;
        $user->update($data);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $users = User::where('name', 'like', "%" . $keyword . "%")->orWhere('email', 'like', "%" . $keyword . "%")->orWhere('role', 'like', "%" . $keyword . "%")->paginate(5);
        return view('users.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
