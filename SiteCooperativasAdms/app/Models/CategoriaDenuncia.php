<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaDenuncia extends Model
{
    public $timestamps = true;
    protected $table = 'tb_categoria_denuncia';
    protected $primaryKey = 'idCategoriaDenuncia';
    protected $fillable = ['descricaoCategoriaDenuncia'];

    public function denuncias()
    {
        return $this->hasMany(Denuncia::class, 'idCategoriaDenuncia', 'idCategoriaDenuncia');
    }
}
