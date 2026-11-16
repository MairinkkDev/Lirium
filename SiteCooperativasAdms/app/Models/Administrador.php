<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    public $timestamps = true;
    protected $table = 'tb_administrador';
    protected $primaryKey = 'idAdministrador';
    protected $fillable = ['idUsuario'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
