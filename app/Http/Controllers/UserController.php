<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
  

    public function register(RegisterRequest $request)
    {

        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']), // Parolayı şifrele
        ]);

        $response = response()->json(['message' => 'Kullanıcı başarıyla eklenmiştir.', 'user' => $user], 200);

        return $response;
    }
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            $user = Auth::user();
            
            $response = response()->json(['message' => 'Oturum açma başarılı.', 'user' => $user], 200);
        } else {
            $response = response()->json(['message' => 'Oturum açma başarısız. Lütfen giriş bilgilerinizi kontrol edin.'], 401);
        }

        // JSON yanıtı döndür
        return $response;
    }

    public function getUserWithSubscriptionsAndTransactions($userId)
    {
       $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadı.'], 404);
        }

         $subscriptions = $user->subscriptions()->with('transactions')->get();

        return response()->json(['user' => $user, 'subscriptions' => $subscriptions], 200);

    }
}
