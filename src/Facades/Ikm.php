<?php

namespace Bale\Ikm\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bale\Ikm\Ikm
 */
class Ikm extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bale\Ikm\Ikm::class;
    }
}
