<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Unit;

use PHPStan\Testing\PHPStanTestCase;
use PHPStanCakePHP2\Service\SchemaService;

class SchemaServiceTest extends PHPStanTestCase
{
    /**
     * @return void
     */
    public function testHasTable(): void
    {
        $schemaService = self::getContainer()->getByType(SchemaService::class);

        $this->assertTrue($schemaService->hasTable('basic_models'));
        $this->assertTrue($schemaService->hasTable('table_without_models'));
        $this->assertFalse($schemaService->hasTable('unknown_tables'));
        // Properties of CakeSchema itself are not tables.
        $this->assertFalse($schemaService->hasTable('tables'));
    }

    /**
     * @return void
     */
    public function testGetTableSchema(): void
    {
        $schemaService = self::getContainer()->getByType(SchemaService::class);

        $tableSchema = $schemaService->getTableSchema('basic_models');
        $this->assertIsArray($tableSchema);
        $this->assertArrayHasKey('id', $tableSchema);
        $this->assertArrayHasKey('indexes', $tableSchema);

        $this->assertNull($schemaService->getTableSchema('unknown_tables'));
    }

    /**
     * @inheritDoc
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../Feature/data/phpstan.neon',
        ];
    }
}
