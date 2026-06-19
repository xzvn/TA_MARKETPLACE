<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id_freelancer',
    'nama_jasa',
    'kategori',
    'deskripsi',
    'harga',
    'estimasi_pengerjaan',
    'thumbnail',
    'status_jasa',
])]
class Jasa extends Model
{
    use HasFactory;

    protected $table = 'jasa';

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'id_freelancer');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'id_jasa');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'id_jasa');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_jasa');
    }

    
}
