<?php

namespace Bonfire\Libraries\Controllers;

class AdminController extends ThemedController {

    protected $uikit = 'Bootstrap3';

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->template->setTheme('admin');
    }

    //--------------------------------------------------------------------


}