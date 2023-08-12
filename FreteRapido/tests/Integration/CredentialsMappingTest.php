<?php

declare(strict_types=1);

namespace FreteRapido\Tests\Integration;

use FreteRapido\Credentials;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CredentialsMappingTest extends TestCase
{
    #[Test]
    public function creating_from_valid_json()
    {
        $cnpj = fake('pt_BR')->cnpj(false);
        $token = str_replace('-', '', fake('pt_BR')->uuid());
        $code = fake('pt_BR')->word();
        $json = <<<JSON
{
    "cnpj": "{$cnpj}",
    "token": "{$token}",
    "codigo_plataforma": "{$code}"
}
JSON;
        $expectedObject = new Credentials($cnpj, $token, $code);

        $credentials = Credentials::from($json);

        $this->assertEquals($expectedObject, $credentials);
    }

    #[Test]
    public function invalid_json_returns_exception()
    {
        $cnpj = fake('pt_BR')->cnpj(false);
        $json = <<<JSON
{
    "cnpj": "{$cnpj}",
    "token": ""
}
JSON;
        try {
            Credentials::from($json);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $this->assertArrayHasKey('token', $errors);
            $this->assertArrayHasKey('codigo_plataforma', $errors);
        }
    }
}
