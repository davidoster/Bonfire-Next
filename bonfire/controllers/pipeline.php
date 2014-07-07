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
        $method = $this->uri->uri_string();
        $method = ltrim( str_ireplace(BF_ASSET_PATH, '', $method), '/ ');

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

        // Allow theme replacement for directives
        $pipeline->registerTag('themed', function ($str) {
            if (preg_match('/{theme:[a-zA-Z]+}/', $str, $matches) !== false)
            {
                if (isset($matches[0]))
                {
                    $theme_path = APPPATH .'../themes/';
                    $theme = trim( str_replace('theme:', '', $matches[0]), '{} ');

                    return $theme_path . $theme .'/'. str_replace($matches[0] .'/', '', $str);
                }
            }

            return $str;
        });

        $output = $pipeline->process( );

        $this->output->enable_profiler(FALSE)
                     ->set_content_type('text/css')
                     ->set_output($output);
    }

    //--------------------------------------------------------------------

}