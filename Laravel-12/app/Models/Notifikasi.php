<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id_user',
    'judul',
    'pesan',
    'tipe',
    'url',
    'dibaca',
    'dibaca_pada',
])]
class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
