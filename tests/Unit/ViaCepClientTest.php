<?php

namespace Tests\Unit;

use App\Services\ViaCepClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ViaCepClientTest extends TestCase
{
    public function test_retorna_os_dados_quando_o_cep_existe(): void
    {
        Http::fake([
            'viacep.com.br/ws/79002100/json/' => Http::response([
                'cep' => '79002-100',
                'logradouro' => 'Rua Exemplo',
                'bairro' => 'Centro',
                'localidade' => 'Campo Grande',
                'uf' => 'MS',
                'ibge' => '5002704',
            ]),
        ]);

        $dados = (new ViaCepClient())->buscar('79002100');

        $this->assertSame('Campo Grande', $dados['localidade']);
        Http::assertSent(fn ($request) => $request->url() === 'https://viacep.com.br/ws/79002100/json/');
    }

    public function test_retorna_null_quando_o_cep_nao_existe(): void
    {
        Http::fake(['viacep.com.br/*' => Http::response(['erro' => 'true'])]);

        $this->assertNull((new ViaCepClient())->buscar('99999999'));
    }

    public function test_retorna_null_quando_o_formato_e_invalido(): void
    {
        Http::fake(['viacep.com.br/*' => Http::response('Bad Request', 400)]);

        $this->assertNull((new ViaCepClient())->buscar('7900210'));
    }

    public function test_lanca_excecao_quando_o_viacep_esta_fora_do_ar(): void
    {
        Http::fake(['viacep.com.br/*' => Http::response('', 500)]);

        $this->expectException(RequestException::class);

        (new ViaCepClient())->buscar('79002100');
    }
}