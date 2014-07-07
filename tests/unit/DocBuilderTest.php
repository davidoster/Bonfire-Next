<?php

include 'bonfire/modules/docs/libraries/DocBuilder.php';

define('BFPATH', 'bonfire/');
define('APPPATH', 'application/');

class DocBuilderTest extends \Codeception\TestCase\Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    protected $builder;

    protected function _before()
    {
        $this->builder = new DocBuilder();
    }

    protected function _after()
    {
        unset($this->builder);
    }

    //--------------------------------------------------------------------

    public function testClassIsLoaded()
    {
        $this->assertTrue( gettype($this->builder) == 'object' );
        $this->assertEquals( get_class($this->builder), 'DocBuilder');
    }

    //--------------------------------------------------------------------

    public function testDocFolderPaths ()
    {
        $this->builder->addDocFolder('application', 'application/docs');
        $this->builder->addDocFolder('developer', 'bonfire/docs');

        $final = [
            'application'   => realpath('application/docs') .'/',
            'developer'     => realpath('bonfire/docs') .'/'
        ];

        $this->assertEquals( $this->builder->docFolders(), $final );

        unset($final['application']);
        $this->builder->removeDocFolder('Application');

        $this->assertEquals( $this->builder->docFolders(), $final );
    }

    //--------------------------------------------------------------------

    /**
     * Verify that reading in the routes docs works and processes
     * the Markdown, etc.
     */
    public function testReadPageBasics ()
    {
        $this->builder->addDocFolder('developer', 'bonfire/docs');

        // Verify Reads content
        $content = $this->builder->readPage('routes', 'developer');
        $this->assertNotNull($content);

        // Verify Markdown processing
        $this->assertTrue( strpos($content, '<h2>') !== false );
    }

    //--------------------------------------------------------------------

    public function testPostProcessLinkConversion ()
    {
        $site_url = 'http://testsite.com';

        $start = '<a href="docs/developer/test">Test</a>';
        $final = '<a href="'. $site_url .'/docs/developer/test">Test</a>';

        $this->assertEquals($final, $this->builder->postProcess($start, $site_url, $site_url));
    }

    //--------------------------------------------------------------------

    public function testPostProcessNamedAnchorsAllowed ()
    {
        $site_url = 'http://testsite.com';

        $start = '<a name="test"></a>';
        $final = '<a name="test" href=" "/>';

        $this->assertEquals($final, $this->builder->postProcess($start, $site_url, $site_url));
    }

    //--------------------------------------------------------------------

    public function testPostProcessConvertsLinksToNamedAnchors ()
    {
        $site_url = 'http://testsite.com';
        $current_url = 'http://testsite.com/docs/test';

        $start = '<a href="#test">Test</a>';
        $final = '<a href="'. $current_url .'#test">Test</a>';

        $this->assertEquals($final, $this->builder->postProcess($start, $site_url, $current_url));
    }

    //--------------------------------------------------------------------

    public function testPostProcessLinkConversionHandlesLocalFullLinks ()
    {
        $site_url = 'http://testsite.com';

        $start = '<a href="http://testsite.com/docs/developer/test">Test</a>';
        $final = '<a href="'. $site_url .'/docs/developer/test">Test</a>';

        $this->assertEquals($final, $this->builder->postProcess($start, $site_url, $site_url));
    }

    //--------------------------------------------------------------------

    public function testPostProcessAddsTableClasses ()
    {
        $site_url = 'http://testsite.com';
        $classes = 'myclass your-class';

        $this->builder->setTableClasses($classes);

        $start = '<table><tbody></tbody></table>';
        $final = '<table class="'. $classes .'"><tbody/></table>';

        $this->assertEquals($final, $this->builder->postProcess($start, $site_url, $site_url));
    }

    //--------------------------------------------------------------------
}