<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribuidor extends Model
{
    protected $table      = 'tb_contribuidor';
    protected $primaryKey = 'idContribuidor';

    protected $fillable = [
        'nomeContribuidor',
        'cpfContribuidor',

    ];


public function telefones()
{
    return $this->hasMany(TelefoneContribuidor::class, 'idContribuidor', 'idContribuidor');
}
}