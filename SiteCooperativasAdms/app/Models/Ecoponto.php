<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecoponto extends Model
{
    public $timestamps = true;
    protected $table = 'tb_ecoponto';
    protected $primaryKey = 'idEcoponto';
    protected $fillable = [
        'descricaoEcoponto', 'cepEcoponto', 'logradouroEcoponto',
        'numeroEcoponto', 'complementoEcoponto', 'bairroEcoponto',
        'cidadeEcoponto', 'ufEcoponto', 'paisEcoponto', 'statusEcoponto',
    ];

    public function materiais()
    {
        return $this->belongsToMany(
            MaterialReciclavel::class,
            'tbEcopontoMaterial',
            'idEcoponto',
            'idMaterial',
            'idEcoponto',
            'idMaterialReciclavel'
        );
    }
}
