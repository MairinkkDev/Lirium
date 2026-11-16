<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

public function store(Request $request)
 {
 $user = new User();
 $user->name = $request->txNome;
 $user->email = $request->txEmail;
 $user -> password = Hash::make($request->txSenha);
 $user->created_at = date('Y-m-d');
 $user->updated_at = date('Y-m-d');
 $user ->save();


//Auth::login($user);

return redirect('dash')->with('mensagem', 'Usuário criado com sucesso!');

 }

 public function fazerLogin(Request $request){
 if(!Auth::attempt($request->only(['email','password']))){
 return redirect('/home');
 }
 else{
 return redirect('dash');
 }
 }

public function fazerLogOut(Request $request){
 Auth::logout();
 return redirect('/login');
 }
    
}
