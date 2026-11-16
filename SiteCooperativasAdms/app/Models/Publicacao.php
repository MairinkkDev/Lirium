<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicacao extends Model
{
    protected $table = 'tb_publicacao';
    protected $primaryKey = 'idPublicacao';
    public $timestamps = false;
    protected $fillable = [
        'tituloPublicacao', 'descricaoPublicacao',
        'dataCriacaoPublicacao', 'statusPublicacao',
        'idInstituicao', 'idCategoriaPublicacao'
    ];

    protected $casts = ['dataCriacaoPublicacao' => 'datetime'];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'idInstituicao', 'idInstituicao');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaPublicacao::class, 'idCategoriaPublicacao', 'idCategoriaPublicacao');
    }

    public function imagens()
    {
        return $this->hasMany(ImagemPublicacao::class, 'idPublicacao', 'idPublicacao');
    }
}
