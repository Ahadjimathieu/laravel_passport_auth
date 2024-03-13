<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use ILLUMINATE\Support\Facades\Auth;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelReader;
class AuthController extends Controller
{
    //

    public function excel(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            Excel::import(new ProductsImport, request()->file('excel_file'));
            return "Fichier Excel traité avec succès.";
        } else {
            return "Aucun fichier Excel n'a été envoyé.";
        }

    }
    public function register(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',


        ]);

        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password']),
        ]);

        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ],200);
    }

    public function login(Request $request)
    {

        $attrs = $request->validate([

            'email' => 'required|email',
            'password' => 'required|min:6',


        ]);

        if(!Auth::attempt($attrs)){

            return response([
                'message' => 'Invalid credentials'
            ],403);
        }

        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'logout success'
        ]);

    }

    public function show(User $user){
        return response([
            'user' => auth()->user()
        ],200);

    }

    public function update(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);
        $image = $this->saveImage($request->image,'profiles');

        $id = auth()->user()->id;
        User::where('active', 1)
                    ->where('id', $id)
                    ->update([ 'name' =>  $attrs['name'],
                    'image' => $image]);

        return response([
            'message' => 'User updated',
            'user' => auth()->user()
        ],200);

    }
}
