<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContribuicaoUsuario extends Model
{
    protected $table = 'tb_contribuicao_usuario';
    protected $primaryKey = 'idContribuicaoUsuario';

    protected $fillable = [
        'idUsuario', 'idInstituicao', 'idMaterialReciclavel',
        'quantidadeKg', 'precoKgNoMomento',
    ];

    public function material()
    {
        return $this->belongsTo(MaterialReciclavel::class, 'idMaterialReciclavel', 'idMaterialReciclavel');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}