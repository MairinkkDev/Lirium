<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueInstituicao extends Model
{
    protected $table = 'tb_estoque_instituicao';
    protected $primaryKey = 'idEstoqueInstituicao';

    protected $fillable = ['idInstituicao', 'idMaterialReciclavel', 'quantidadeKg'];

    public function material()
    {
        return $this->belongsTo(MaterialReciclavel::class, 'idMaterialReciclavel', 'idMaterialReciclavel');
    }
}