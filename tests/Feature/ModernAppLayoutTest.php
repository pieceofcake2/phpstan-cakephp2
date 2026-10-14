<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

/**
 * Tests the default paths for the pieceofcake2/app layout:
 * src/Model/Behavior, plugins/*\/Model/Behavior and config/Schema.
 */
class ModernAppLayoutTest extends AppLayoutTestCase
{
    /**
     * @inheritDoc
     */
    protected static function getAppRoot(): string
    {
        return __DIR__ . '/apps/modern';
    }

    /**
     * @return void
     */
    public function testModernAppLayout(): void
    {
        $this->assertTypesInFileFromAppRoot(__DIR__ . '/data/modern_app_layout.php');
    }
}
