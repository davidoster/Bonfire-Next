<?php

namespace Bonfire\Controllers;

use Bonfire\ThemeableTrait;

class ThemedController extends BaseController {

    use ThemeableTrait;

    /**
     * Let's the BaseController's init() method
     * know which methods to run.
     *
     * @var array
     */
    protected $init_methods = [
        'init_themes'
    ];

    //--------------------------------------------------------------------

}