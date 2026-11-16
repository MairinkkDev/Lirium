<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemInstituicao extends Model
{
    protected $table = 'tb_imagem_instituicao';
    protected $primaryKey = 'idImagemInstituicao';
    public $timestamps = true;
    protected $fillable = ['caminhoImagemInstituicao', 'idInstituicao'];
}