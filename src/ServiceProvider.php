<?php

namespace Benfurfie\StatamicSvg;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $modifiers = [
        Modifiers\Svg::class
    ];
}
