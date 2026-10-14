<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Feature;

use RuntimeException;

/**
 * Base class for tests of the default behavior and schema paths in extension.neon.
 *
 * The default paths are relative to the working directory, so the data file is
 * analysed from the root of a fixture application under tests/Feature/apps.
 */
abstract class AppLayoutTestCase extends FeatureTestCase
{
    /**
     * Returns the root directory of the fixture application.
     *
     * @return string
     */
    abstract protected static function getAppRoot(): string;

    /**
     * Analyses the given file from the root of the fixture application.
     *
     * @param string $file Absolute path of the file to analyse.
     * @return void
     */
    protected function assertTypesInFileFromAppRoot(string $file): void
    {
        $cwd = getcwd();
        if ($cwd === false || !chdir(static::getAppRoot())) {
            throw new RuntimeException('Unable to change to the fixture application directory.');
        }

        try {
            $this->assertTypesInFile($file);
        } finally {
            chdir($cwd);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [
            static::getAppRoot() . '/phpstan.neon',
        ];
    }
}
