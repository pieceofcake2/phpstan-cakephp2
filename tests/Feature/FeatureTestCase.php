<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

use PHPStan\Testing\TypeInferenceTestCase;

/**
 * Base class for tests which analyse a data file and check its assertType() calls.
 *
 * The data file is analysed inside the test method rather than in a data provider,
 * so that the extensions' code is executed while PHPUnit is collecting coverage.
 */
abstract class FeatureTestCase extends TypeInferenceTestCase
{
    /**
     * Analyses the given file and checks every assertType() call in it.
     *
     * @param string $file Absolute path of the file to analyse.
     * @return void
     */
    protected function assertTypesInFile(string $file): void
    {
        $asserts = self::gatherAssertTypes($file);
        $this->assertNotEmpty($asserts, sprintf('%s does not contain any assertions.', $file));

        foreach ($asserts as $args) {
            $assertType = array_shift($args);
            $assertFile = array_shift($args);
            $this->assertIsString($assertType);
            $this->assertIsString($assertFile);
            $this->assertFileAsserts($assertType, $assertFile, ...$args);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/data/phpstan.neon',
        ];
    }
}
