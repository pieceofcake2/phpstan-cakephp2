<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

class ControllerExtensionsTest extends FeatureTestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function dataFiles(): iterable
    {
        yield 'existing_controller_model' => [__DIR__ . '/data/existing_controller_model.php'];
        yield 'existing_controller_component' => [__DIR__ . '/data/existing_controller_component.php'];
        yield 'invalid_controller_property' => [__DIR__ . '/data/invalid_controller_property.php'];
        yield 'existing_controller_component_with_same_method_name_as_model' => [__DIR__ . '/data/existing_controller_component_with_same_method_name_as_model.php'];
        yield 'existing_controller_component_from_parent_controller' => [__DIR__ . '/data/existing_controller_component_from_parent_controller.php'];
        yield 'existing_controller_component_with_settings' => [__DIR__ . '/data/existing_controller_component_with_settings.php'];
    }

    /**
     * @dataProvider dataFiles
     */
    public function testControllerExtensions(string $file): void
    {
        $this->assertTypesInFile($file);
    }
}
