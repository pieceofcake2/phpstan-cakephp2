<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

/**
 * Tests the default paths for the legacy CakePHP 2 app layout:
 * app/Model/Behavior, app/Plugin/*\/Model/Behavior and app/Config/Schema.
 */
class LegacyAppLayoutTest extends AppLayoutTestCase
{
    /**
     * @inheritDoc
     */
    protected static function getAppRoot(): string
    {
        return __DIR__ . '/apps/legacy';
    }

    /**
     * @return void
     */
    public function testLegacyAppLayout(): void
    {
        $this->assertTypesInFileFromAppRoot(__DIR__ . '/data/legacy_app_layout.php');
    }
}
