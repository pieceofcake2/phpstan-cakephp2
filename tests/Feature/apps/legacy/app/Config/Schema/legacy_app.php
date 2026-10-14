<?php
declare(strict_types=1);

class LegacyAppSchema extends CakeSchema
{
    /**
     * @var array<string, mixed>
     */
    public $legacy_articles = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
    ];
}
