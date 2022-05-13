<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $data = [
            'users' => User::latest()->get()
        ];

        return view('users',$data);
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required|min:6',
            'image'     => 'required',
        ]);

        $request['password'] = bcrypt($request->password);

        $user = User::create($request->all());

        if($request->hasFile('image') && $request->file('image')->isValid()){
            $user->addMediaFromRequest('image')->toMediaCollection('images');
        }

        session()->flash('success','User Created Successfully');
        return redirect()->route('users.index');
    }
}
