<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecoInstituicao extends Model
{
    protected $table = 'tb_endereco_instituicao';
    protected $primaryKey = 'idEnderecoInstituicao';
    public $timestamps = true;
    protected $fillable = [
        'cepEnderecoInstituicao', 'logradouroEnderecoInstituicao',
        'numeroEnderecoInstituicao', 'complementoEnderecoInstituicao',
        'bairroEnderecoInstituicao', 'cidadeEnderecoInstituicao',
        'ufEnderecoInstituicao', 'idInstituicao'
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'idInstituicao', 'idInstituicao');
    }
}
