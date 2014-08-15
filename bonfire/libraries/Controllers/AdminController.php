<?php

namespace Bonfire\Libraries\Controllers;

class AdminController extends ThemedController {

    public function __construct()
    {
        parent::__construct();

        $this->template->setTheme('admin');
    }

    //--------------------------------------------------------------------


}