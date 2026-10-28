<?php

namespace Database\Factories;

use App\Models\Cep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cep>
 */
class CepFactory extends Factory
{
    private const CIDADES = [
        ['localidade' => 'Campo Grande',   'uf' => 'MS', 'estado' => 'Mato Grosso do Sul', 'regiao' => 'Centro-Oeste', 'ibge' => '5002704', 'ddd' => '67'],
        ['localidade' => 'São Paulo',      'uf' => 'SP', 'estado' => 'São Paulo',          'regiao' => 'Sudeste',      'ibge' => '3550308', 'ddd' => '11'],
        ['localidade' => 'Rio de Janeiro', 'uf' => 'RJ', 'estado' => 'Rio de Janeiro',     'regiao' => 'Sudeste',      'ibge' => '3304557', 'ddd' => '21'],
        ['localidade' => 'Curitiba',       'uf' => 'PR', 'estado' => 'Paraná',             'regiao' => 'Sul',          'ibge' => '4106902', 'ddd' => '41'],
    ];

    private const BAIRROS = ['Centro', 'Jardim dos Estados', 'Vila Nova', 'Santa Fé', 'Jardim América', 'Bela Vista'];

    private const TIPOS_LOGRADOURO = ['Rua', 'Avenida', 'Travessa', 'Alameda'];

    public function definition(): array
    {
        $cidade = fake()->randomElement(self::CIDADES);

        return [
            'cep' => fake()->unique()->numerify('########'),
            'logradouro' => fake()->randomElement(self::TIPOS_LOGRADOURO).' '.fake()->lastName(),
            'complemento' => null,
            'unidade' => null,
            'bairro' => fake()->randomElement(self::BAIRROS),
            'localidade' => $cidade['localidade'],
            'uf' => $cidade['uf'],
            'estado' => $cidade['estado'],
            'regiao' => $cidade['regiao'],
            'ibge' => $cidade['ibge'],
            'ddd' => $cidade['ddd'],
            'gia' => null,
            'siafi' => null,
            'sincronizado_em' => now(),
        ];
    }

    /**
     * CEPs dentro de uma faixa.
     */
    public function doPrefixo(string $prefixo): static
    {
        return $this->state(fn () => [
            'cep' => $prefixo.fake()->unique()->numerify(str_repeat('#', 8 - strlen($prefixo))),
        ]);
    }

    /**
     * CEP em Campo Grande/MS.
     */
    public function campoGrande(): static
    {
        return $this->state(fn () => self::CIDADES[0]);
    }
}