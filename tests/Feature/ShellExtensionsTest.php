<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

class ShellExtensionsTest extends FeatureTestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function dataFiles(): iterable
    {
        yield 'existing_shell_model' => [__DIR__ . '/data/existing_shell_model.php'];
        yield 'existing_shell_task' => [__DIR__ . '/data/existing_shell_task.php'];
        yield 'existing_shell_task_named_like_model' => [__DIR__ . '/data/existing_shell_task_named_like_model.php'];
        yield 'existing_shell_property_tag' => [__DIR__ . '/data/existing_shell_property_tag.php'];
        yield 'invalid_shell_property' => [__DIR__ . '/data/invalid_shell_property.php'];
    }

    /**
     * @dataProvider dataFiles
     */
    public function testShellExtensions(string $file): void
    {
        $this->assertTypesInFile($file);
    }
}
