<?php
declare(strict_types=1);

/**
 * @property SecondModel $BasicModel
 * @property SecondComponent $Basic
 */
class PropertyTagController extends Controller
{
    /**
     * @var array<array-key, string>
     */
    public $components = ['Basic'];
}
