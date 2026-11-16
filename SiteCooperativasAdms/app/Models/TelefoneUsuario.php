<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelefoneUsuario extends Model
{
    protected $table = 'tb_telefone_usuario';
    protected $primaryKey = 'idTelefoneUsuario';
    public $timestamps = true;
    protected $fillable = ['numTelefoneUsuario', 'idUsuario'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}