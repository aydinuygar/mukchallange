<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderUser extends Model
{
    use HasFactory;
     protected $fillable = [
        'user_id', 'payment_provider',
    ];

    // Eğer user_id'ye ilişkili bir User modeli varsa, ilişkiyi tanımlayabilirsiniz
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
