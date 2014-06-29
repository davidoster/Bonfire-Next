<?php

class Pipeline extends Base_Controller {

    /**
     * Simple funnel everything to the index method
     * and we'll deal with it there.
     *
     * @param $method
     */
    public function _remap ($method)
    {
        $this->index($method);
    }

    //--------------------------------------------------------------------

    public function index ($asset=null)
    {
        $this->config->load('assets', true);

        $config = config_item('assets');
        $filters = $config['filters'];
        unset($config['filters']);

        $pipeline = new \Bonfire\Assets\AssetPipeline($asset, $config, $filters);

        echo '<pre>'. print_r($pipeline->process( ), true);
    }

    //--------------------------------------------------------------------

}