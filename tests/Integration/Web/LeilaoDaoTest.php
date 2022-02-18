<?php

namespace Alura\Leilao\Tests\Integration;

use PHPUnit\Framework\TestCase;

class RestTest extends TestCase
{
   
    public function testApiRestDeveRetornarArrayDeLeiloes()
    {
        $resposta = file_get_contents('http://192.168.0.25:8081/rest.php');

        self::assertStringContainsString('200 OK', $http_response_header[0]);
        self::assertIsArray(json_decode($resposta));
    }

}
