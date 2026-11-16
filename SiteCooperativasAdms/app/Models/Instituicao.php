<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    protected $table = 'tb_instituicao';

    protected $primaryKey = 'idInstituicao';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'idTipoInstituicao',
        'nomeInstituicao',
        'descricaoInstituicao',
        'statusInstituicao',
        'cnpjInstituicao',
        'emailInstituicao'
    ];

    // Uma instituição pertence a um tipo.
   public function tipoInstituicao()
{
    return $this->belongsTo(
        TipoInstituicao::class,
        'idTipoInstituicao',
        'idTipoInstituicao'
    );
}

public function getFotoUrlAttribute(): ?string
{
    $img = $this->imagem;

    if (!$img || !$img->caminhoImagemInstituicao) {
        return null;
    }

    return route('foto.perfil', ['tipo' => 'instituicao', 'id' => $this->idInstituicao])
        . '?v=' . $img->updated_at?->timestamp;
}

public function imagem()
{
    return $this->hasOne(ImagemInstituicao::class, 'idInstituicao', 'idInstituicao');
}

public function telefones()
{
    return $this->hasMany(
        TelefoneInstituicao::class,
        'idInstituicao',
        'idInstituicao'
    );
}

public function endereco()
{
    return $this->hasOne(
        EnderecoInstituicao::class,
        'idInstituicao',
        'idInstituicao'
    );
}
public function eventos()
{
    return $this->belongsToMany(
        Evento::class,
        'tb_evento_instituicao',
        'idInstituicao',   // FK desta tabela na ligação
        'idEvento'         // FK do evento na ligação
    )->withTimestamps();
}
    // Uma instituição pode estar vinculada a um usuário.
    public function usuario()
    {
        return $this->hasOne(
            Usuario::class,
            'idInstituicao',
            'idInstituicao'
        );
    }
}
