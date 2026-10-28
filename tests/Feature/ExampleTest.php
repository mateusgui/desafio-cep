<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_a_pagina_inicial_responde_200(): void
    {
        $this->get('/')->assertStatus(200);
    }
}