<?php
declare(strict_types=1);

class SameAsModelController extends BaseController
{
    /**
     * @var array<array-key, string>
     */
    public $components = [
        'SameAsModel',
        BasicComponent::class,
    ];
}
