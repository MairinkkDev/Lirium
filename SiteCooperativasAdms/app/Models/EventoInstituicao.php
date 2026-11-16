<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoInstituicao extends Model
{
    use HasFactory;

    protected $table = 'tb_evento_instituicao';

    protected $primaryKey = 'idEventoInstituicao';

    public $timestamps = true;

    protected $fillable = [
        'idEvento',
        'idInstituicao',
    ];

   
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'idEvento', 'idEvento');
    }


    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class, 'idInstituicao', 'idInstituicao');
    }
}