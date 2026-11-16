<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'tb_usuario';

    protected $primaryKey = 'idUsuario';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'nomeUsuario',
        'emailUsuario',
        'cpfUsuario',
        'senhaUsuario',
        'statusUsuario',
        'idNivelAcesso',
        'idInstituicao'
    ];

    protected $hidden = [
        'senhaUsuario'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    'updated_at' => 'datetime',
        'ultimoAcessoUsuario' => 'datetime',
        'email_verified_at' => 'datetime'
    ];

    public function getAuthPassword()
    {
        return $this->senhaUsuario;
    }

    public function getAuthPasswordName()
    {
        return 'senhaUsuario';
    }

    public function getRememberTokenName()
    {
        return '';
    }

    // Um usuário pertence a uma instituição.
    public function instituicao()
    {
        return $this->belongsTo(
            Instituicao::class,
            'idInstituicao',
            'idInstituicao'
        );
    }

    public function getFotoUrlAttribute(): ?string
{
    $img = $this->imagem;

    if (!$img || !$img->caminhoImagemUsuario) {
        return null;
    }

    return route('foto.perfil', ['tipo' => 'usuario', 'id' => $this->idUsuario])
        . '?v=' . $img->updated_at?->timestamp;
}

public function imagem()
{
    return $this->hasOne(ImagemUsuario::class, 'idUsuario', 'idUsuario');
}
    public function isAdmin(): bool
{
    return $this->idNivelAcesso == 3;
}

public function isInstituicao(): bool
{
    return  $this->idNivelAcesso == 2;
}

    // Um usuário pertence a um nível de acesso.
    public function nivelAcesso()
    {
        return $this->belongsTo(
            NivelAcesso::class,
            'idNivelAcesso',
            'idNivelAcesso'
        );
    }
}