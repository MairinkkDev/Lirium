<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaPublicacao extends Model
{
    public $timestamps = true;
    protected $table = 'tb_categoria_publicacao';
    protected $primaryKey = 'idCategoriaPublicacao';
    protected $fillable = ['descricaoCategoriaPublicacao'];

    public function publicacoes()
    {
        return $this->hasMany(Publicacao::class, 'idCategoriaPublicacao', 'idCategoriaPublicacao');
    }
}
