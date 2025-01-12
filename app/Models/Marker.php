<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'latitude', 'longitude', 'layanan_kesehatan', 'jam_operasional', 'no_telpon', 'alamat', 'foto'];

    // Jika memanfaatkan accessor atau mutator untuk foto
    public function getFotoAttribute($value)
    {
        return asset('storage/' . $value); // Jika menggunakan storage/app/public
    }
}
