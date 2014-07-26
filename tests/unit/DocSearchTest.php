<?php

include 'bonfire/modules/docs/libraries/DocSearch.php';

define('BFPATH', 'bonfire/');
define('APPPATH', 'application/');

class DocSearchTest extends \Codeception\TestCase\Test {

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