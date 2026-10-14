<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

class ComponentExtensionsTest extends FeatureTestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function dataFiles(): iterable
    {
        yield 'existing_component_component' => [__DIR__ . '/data/existing_component_component.php'];
        yield 'invalid_component_property' => [__DIR__ . '/data/invalid_component_property.php'];
    }

    /**
     * @dataProvider dataFiles
     */
    public function testComponentExtensions(string $file): void
    {
        $this->assertTypesInFile($file);
    }
}
