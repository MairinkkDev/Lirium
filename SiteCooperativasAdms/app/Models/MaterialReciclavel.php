<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialReciclavel extends Model
{
    protected $table = 'tb_material_reciclavel';
    protected $primaryKey = 'idMaterialReciclavel';
    public $timestamps = true;
    protected $fillable = [
        'nomeMaterialReciclavel', 'descricaoMaterialReciclavel',
        'tipoMaterialReciclavel', 'instrucoesDescarteMaterialReciclavel'
    ];

    public function ecopontos()
    {
        return $this->belongsToMany(
            Ecoponto::class,
            'tbEcopontoMaterial',
            'idMaterial',
            'idEcoponto',
            'idMaterialReciclavel',
            'idEcoponto'
        );
    }
}
