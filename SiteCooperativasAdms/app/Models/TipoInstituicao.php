<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoInstituicao extends Model
{
    protected $table = 'tb_tipo_instituicao';

    protected $primaryKey = 'idTipoInstituicao';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nomeTipoInstituicao'
    ];

    public function instituicoes()
    {
        return $this->hasMany(
            Instituicao::class,
            'idTipoInstituicao',
            'idTipoInstituicao'
        );
    }
}
