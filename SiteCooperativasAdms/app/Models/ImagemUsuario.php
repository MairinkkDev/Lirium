<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemUsuario extends Model
{
    protected $table = 'tb_imagem_usuario';
    protected $primaryKey = 'idImagemUsuario';
    public $timestamps = true;
    protected $fillable = ['caminhoImagemUsuario', 'idUsuario'];

}