<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoEvento extends Model
{
    protected $table = 'tb_evento_avaliacao';

    protected $primaryKey = 'idEventoAvaliacao';

    protected $fillable = [
        'idEvento',
        'idUsuario',
        'notaAvaliacao',
        'descricaoAvaliacao',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'idEvento', 'idEvento');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}