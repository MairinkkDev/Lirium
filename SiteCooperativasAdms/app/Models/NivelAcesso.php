<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelAcesso extends Model
{
    protected $table = 'tb_nivel_acesso';

    protected $primaryKey = 'idNivelAcesso';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nomeNivelAcesso'
    ];

    public function usuarios()
    {
        return $this->hasMany(
            Usuario::class,
            'idNivelAcesso',
            'idNivelAcesso'
        );
    }
}