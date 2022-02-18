<?php

namespace Alura\Leilao\Tests\Integration;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PDO;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    /** @var PDO */
    private static $pdo;
    public static function setUpBeforeClass(): void
    {

        self::$pdo = new PDO('sqlite::memory:');
        $sql = 'CREATE TABLE leiloes (
                    id INTEGER PRIMARY KEY,
                    descricao TEXT,
                    finalizado BOOL,
                    dataInicio TEXT
                )';
        self::$pdo->exec($sql);
    }


    protected function setUp(): void
    {
        // $this->pdo = ConnectionCreator::getConnection();
        self::$pdo->beginTransaction();
    }

    /**
     * @dataProvider leiloes
     */
    public function testeBuscarLeiloesNaoFinalizados(array $leiloes)
    {
        $leilaoDao = new LeilaoDao(self::$pdo);

        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
            // assert intermediarios
        }

        // act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        // asssert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Variante 0 km', $leiloes[0]->recuperarDescricao());
        self::assertFalse($leiloes[0]->estaFinalizado());
    }

    /**
     * @dataProvider leiloes
     */
    public function testeBuscarLeiloesFinalizados(array $leiloes)
    {
        $leilaoDao = new LeilaoDao(self::$pdo);

        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        $leiloes = $leilaoDao->recuperarFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Flat 147 0km', $leiloes[0]->recuperarDescricao());
        self::assertTrue($leiloes[0]->estaFinalizado());
    }

    public function testAoAtualizarLeilaoStatusDeveSerAlterado()
    {
        $leilao = new Leilao('Brasília Amarela');
        $leilaoDao = new LeilaoDao(self::$pdo);
        $leilao = $leilaoDao->salva($leilao);

        $leiloes = $leilaoDao->recuperarNaoFinalizados();
        self::assertCount(1, $leiloes);
        self::assertSame('Brasília Amarela', $leiloes[0]->recuperarDescricao());
        self::assertFalse($leiloes[0]->estaFinalizado());

        $leilao->finaliza();
        $leilaoDao->atualiza($leilao);

        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(1, $leiloes);
        self::assertSame('Brasília Amarela', $leiloes[0]->recuperarDescricao());
        self::assertTrue($leiloes[0]->estaFinalizado());
    }

    public function leiloes()
    {
        $leilaoNaoFinalizado = new Leilao('Variante 0 km');
        $leilaoFinalizado = new Leilao('Flat 147 0km');
        $leilaoFinalizado->finaliza();
        return [
            [
                [$leilaoNaoFinalizado, $leilaoFinalizado]
            ]
        ];
    }

    protected function tearDown(): void
    {
        // cleardow deletar os dados no banco para não falhar os testes
        self::$pdo->rollBack();
    }
}
