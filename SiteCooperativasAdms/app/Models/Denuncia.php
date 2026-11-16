<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    public $timestamps = true;
    protected $table = 'tb_denuncia';
    protected $primaryKey = 'idDenuncia';
    protected $fillable = [
        'descricaoDenuncia', 'dataCriacaoDenuncia',
        'statusDenuncia', 'idUsuario', 'idCategoriaDenuncia'
    ];

    protected $casts = ['dataCriacaoDenuncia' => 'datetime'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaDenuncia::class, 'idCategoriaDenuncia', 'idCategoriaDenuncia');
    }
}
