<?php

class Demo extends \Bonfire\Libraries\Controllers\ThemedController {

    protected $uikit = null;
    protected $uikit_name = null;

    //--------------------------------------------------------------------

    public function __construct($uikit='Bootstrap3')
    {
        // Determine the UIKit to use based on the $_GET['uikit'] variable.
        if (isset($_GET['uikit']))
        {
            $uikit = $_GET['uikit'];
        }

        $this->uikit = $this->uikit_name = $uikit;

        parent::__construct();
    }

    //--------------------------------------------------------------------

    public function index()
    {
        $data = [
            'uikit' => $this->uikit
        ];

        switch ($this->uikit_name)
        {
            case 'Bootstrap3':
                $data['stylesheet'] = 'http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css';
                break;
            case 'Foundation5':
                $data['stylesheet'] = 'http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.1/css/foundation.css';
                break;
            case 'Pure05':
                $data['stylesheet'] = 'http://yui.yahooapis.com/pure/0.5.0/pure-min.css';
                break;
        }

        $this->load->view('uikit_demo', $data);
    }

    //--------------------------------------------------------------------


}