<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

class ModelExtensionsTest extends FeatureTestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function dataFiles(): iterable
    {
        yield 'core_model_behavior' => [__DIR__ . '/data/core_model_behavior.php'];
        yield 'custom_model_behavior' => [__DIR__ . '/data/custom_model_behavior.php'];
        yield 'invalid_model_property' => [__DIR__ . '/data/invalid_model_property.php'];
        yield 'existing_model_model' => [__DIR__ . '/data/existing_model_model.php'];
    }

    /**
     * @dataProvider dataFiles
     */
    public function testModelExtensions(string $file): void
    {
        $this->assertTypesInFile($file);
    }
}
