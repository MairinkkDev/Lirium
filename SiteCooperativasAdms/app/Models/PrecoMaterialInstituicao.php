<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecoMaterialInstituicao extends Model
{
    protected $table = 'tb_preco_material_instituicao';
    protected $primaryKey = 'idPrecoMaterialInstituicao';

    protected $fillable = ['idInstituicao', 'idMaterialReciclavel', 'precoKg'];

    public function material()
    {
        return $this->belongsTo(MaterialReciclavel::class, 'idMaterialReciclavel', 'idMaterialReciclavel');
    }
}