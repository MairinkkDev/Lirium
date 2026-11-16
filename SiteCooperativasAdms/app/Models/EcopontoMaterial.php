<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcopontoMaterial extends Model
{
    protected $table = 'tb_ecoponto_material';
    protected $primaryKey = 'idEcopontoMaterial';
    public $timestamps = true;
    protected $fillable = ['idEcoponto', 'idMaterial'];

    public function ecoponto()
    {
        return $this->belongsTo(Ecoponto::class, 'idEcoponto', 'idEcoponto');
    }

    public function material()
    {
        return $this->belongsTo(MaterialReciclavel::class, 'idMaterial', 'idMaterialReciclavel');
    }
}
