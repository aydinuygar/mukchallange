<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use Carbon\Carbon;
use App\Models\User;



class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubscriptionRequest $request, $id)
    {

        if (!User::find($id)) {
            return response()->json(['error' => 'Geçersiz kullanıcı ID.'], 422);
        }

        $validatedData = $request->validated();

        $startDate = Carbon::createFromFormat('d.m.Y', $validatedData['start_date']);
        // End date'i start date'e eklenmiş bir ay olarak ayarlandı
        $endDate = $startDate->copy()->addMonth()->toDateString();


        // Kullanıcıya bağlı olarak yeni bir abonelik oluştur
        $subscription = Subscription::create([
            'user_id' => $id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Oluşturulan aboneliği JSON formatında yanıt olarak döndür
        return response()->json(['message' => 'Abonelik başarıyla oluşturuldu.', 'subscription' => $subscription], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubscriptionUpdateRequest $request, $userId, $subscriptionId)
    {

        // İsteği doğrula ve doğrulanmış veriyi al
        $validatedData = $request->validated();

        // Kullanıcıyı bul
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Geçersiz kullanıcı ID.'], 404);
        }

        // Aboneliği bul
        $subscription = $user->subscriptions()->find($subscriptionId);
        if (!$subscription) {
            return response()->json(['error' => 'Geçersiz abonelik ID.'], 404);
        }
        
        // Yenileme tarihini kontrol et
        $updateDate = Carbon::createFromFormat('d.m.Y', $validatedData['update_date'])->toDateString();

        // Güncellenmiş tarih, mevcut son tarihten önce mi kontrol et
        if (strtotime($updateDate) <= strtotime($subscription->end_date)) {
            return response()->json(['error' => 'Yenileme tarihi, mevcut abonelik süresinden sonra olmalıdır.'], 422);
        }

        //geçmiş tarih eklemesi validasyonu
        // Abonelik süresinin son tarihini güncelle
        $subscription->update([
            'end_date' => $updateDate,
        ]);
        
        return response()->json(['message' => 'Abonelik başarıyla güncellendi.', 'subscription' => $subscription], 200);
    }
        


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId, $subscriptionId)
    {
        // Kullanıcıyı bul
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Geçersiz kullanıcı ID.'], 404);
        }

        // Aboneliği bul
        $subscription = $user->subscriptions()->find($subscriptionId);
        if (!$subscription) {
            return response()->json(['error' => 'Geçersiz abonelik ID.'], 404);
        }

        // Aboneliği sil
        $subscription->delete();

        return response()->json(['message' => 'Abonelik başarıyla silindi.'], 200);
    }
}
