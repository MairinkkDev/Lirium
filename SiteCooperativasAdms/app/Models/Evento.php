<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'tb_evento';

    protected $primaryKey = 'idEvento';

    public $timestamps = true;

    // Status dos eventos
    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_AGENDADO = 'agendado';
    public const STATUS_EM_ANDAMENTO = 'em_andamento';
    public const STATUS_CONCLUIDO = 'concluido';
    public const STATUS_CANCELADO = 'cancelado';
    public const STATUS_RECUSADO = 'recusado';

    protected $fillable = [
        'nomeEvento',
        'descricaoEvento',
        'dataInicioEvento',
        'dataFimEvento',
        'cepEnderecoEvento',
        'logradouroEnderecoEvento',
        'numeroEnderecoEvento',
        'complementoEnderecoEvento',
        'bairroEnderecoEvento',
        'cidadeEnderecoEvento',
        'ufEnderecoEvento',
        'statusEvento',
    ];

    // Novos eventos começam aguardando aprovação
    protected $attributes = [
        'statusEvento' => self::STATUS_PENDENTE,
    ];

    protected function casts(): array
    {
        return [
            'dataInicioEvento' => 'datetime',
            'dataFimEvento' => 'datetime',
        ];
    }

    public function eventoInstituicoes(): HasMany
    {
        return $this->hasMany(
            EventoInstituicao::class,
            'idEvento',
            'idEvento'
        );
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(
            AvaliacaoEvento::class,
            'idEvento',
            'idEvento'
        );
    }

    public function instituicoes(): BelongsToMany
    {
        return $this->belongsToMany(
            Instituicao::class,
            'tb_evento_instituicao',
            'idEvento',
            'idInstituicao',
            'idEvento',
            'idInstituicao'
        )->withTimestamps();
    }
}
