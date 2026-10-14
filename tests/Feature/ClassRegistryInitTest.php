<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

class ClassRegistryInitTest extends FeatureTestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function dataFiles(): iterable
    {
        yield 'class_registry_init' => [__DIR__ . '/data/class_registry_init.php'];
    }

    /**
     * @dataProvider dataFiles
     */
    public function testClassRegistryInit(string $file): void
    {
        $this->assertTypesInFile($file);
    }
}
