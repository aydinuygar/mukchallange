<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Requests\TransactionRequest;
use App\Models\User;
use App\Models\ProviderUser;
use App\Notifications\PaymentReceivedNotification;



class TransactionController extends Controller
{
    protected function pay($paymentProvider)
    {
        // Ödeme işlemini yap
        if ($paymentProvider === 'stripe') {
            // Stripe üzerinden ödeme yap
            return true;
        } elseif ($paymentProvider === 'iyzico') {
            // Iyzico üzerinden ödeme yap
            return true;
        } else {
            // Tanımlı bir ödeme sağlayıcısı yoksa hata döndür
            return false;
        }
    }

    public function store(TransactionRequest $request, $userId)
    {
        // Kullanıcıyı bul
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Geçersiz kullanıcı ID.'], 404);
        }

        // İsteği doğrula ve doğrulanmış veriyi al
        $validatedData = $request->validated();

        // Ödeme sağlayıcısını belirle
        $providerUser = ProviderUser::where('user_id', $userId)->first();
        if (!$providerUser) {
            return response()->json(['error' => 'Ödeme sağlayıcısı bulunamadı.'], 404);
        }
        $paymentProvider = $providerUser->payment_provider;

        // Ödeme işlemini yap
        $success = $this->pay($paymentProvider);

        // Ödeme başarılı ise
        if ($success) {
            // Transaction modelini kullanarak yeni bir ödeme işlemi oluştur
            $transaction = Transaction::create([
                'user_id' => $userId,
                'subscription_id' => $validatedData['subscription_id'],
                'price' => $validatedData['price'],
            ]);
            
            

            return response()->json(['message' => 'Ödeme işlemi başarıyla oluşturuldu.', 'transaction' => $transaction], 201);
            // Kullanıcıya bildirim gönder
            $user->notify(new PaymentReceivedNotification);
        }else {
            return response()->json(['error' => 'Ödeme işlemi başarısız.'], 422);
        }
    }  
}
