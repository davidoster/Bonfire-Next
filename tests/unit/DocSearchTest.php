<?php

include 'bonfire/modules/docs/libraries/DocSearch.php';

define('BFPATH', 'bonfire/');
define('APPPATH', 'application/');

if (! function_exists('get_instance')) {
    function get_instance()
    {
        $ci = new stdClass();

        $ci->load         = new stdClass();
        $ci->load->helper = function ($name) {

            return $name;
        };

        return $ci;
    }
}

//--------------------------------------------------------------------

class DocSearchTest extends \Codeception\TestCase\Test
{

    protected $searcher;

    protected function _before()
    {
        $this->searcher = new DocSearch();
    }

    protected function _after()
    {
        unset($this->searcher);
    }

    //--------------------------------------------------------------------

    public function testClassIsLoaded()
    {
        $this->assertTrue(gettype($this->searcher) == 'object');
        $this->assertEquals(get_class($this->searcher), 'DocSearch');
    }
    //--------------------------------------------------------------------
}