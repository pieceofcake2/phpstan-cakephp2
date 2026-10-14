<?php
declare(strict_types=1);

class ModernAppSchema extends CakeSchema
{
    /**
     * @var array<string, mixed>
     */
    public $modern_articles = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
    ];
}
