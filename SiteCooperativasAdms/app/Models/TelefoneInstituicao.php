<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelefoneInstituicao extends Model
{
    protected $table = 'tb_telefone_instituicao';

    protected $primaryKey = 'idTelefoneInstituicao';

    public $timestamps = true;

    protected $fillable = [
        'numTelefoneInstituicao',
        'idInstituicao'
    ];

    public function instituicao()
    {
        return $this->belongsTo(
            Instituicao::class,
            'idInstituicao',
            'idInstituicao'
        );
    }
}