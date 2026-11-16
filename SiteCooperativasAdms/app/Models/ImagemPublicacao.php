<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemPublicacao extends Model
{
    protected $table = 'tb_imagem_publicacao';
    protected $primaryKey = 'idImagemPublicacao';
    public $timestamps = true;
    protected $fillable = ['caminhoImagemPublicacao', 'idPublicacao'];

    public function publicacao()
    {
        return $this->belongsTo(Publicacao::class, 'idPublicacao', 'idPublicacao');
    }
}
