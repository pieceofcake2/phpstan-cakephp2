<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

class LoadComponentOnFlyMethodReturnTypeExtensionTest extends FeatureTestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function dataFiles(): iterable
    {
        yield 'loading_component_loaded_on_fly' => [__DIR__ . '/data/loading_component_loaded_on_fly.php'];
    }

    /**
     * @dataProvider dataFiles
     */
    public function testLoadComponentOnFly(string $file): void
    {
        $this->assertTypesInFile($file);
    }
}
