<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelefoneContribuidor extends Model
{
    protected $table      = 'tb_telefone_contribuidor';
    protected $primaryKey = 'idTelefoneContribuidor';

    protected $fillable = [
        'idContribuidor',
        'numTelefoneContribuidor',
    ];

    public function contribuidor()
    {
        return $this->belongsTo(Contribuidor::class, 'idContribuidor', 'idContribuidor');
    }
}