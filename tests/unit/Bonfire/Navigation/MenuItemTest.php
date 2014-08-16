<?php
namespace Bonfire\Libraries\Navigation;


class MenuItemTest extends \Codeception\TestCase\Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    protected $item;

    protected function _before()
    {
    }

    protected function _after()
    {
        unset($this->item);
    }

    //--------------------------------------------------------------------
    
    public function testCanAssignNameInConstructor()
    {
        $this->item = new MenuItem('item 1');

        $this->assertEquals($this->item->name(), 'item 1');
    }
    
    //--------------------------------------------------------------------

    public function testCanAssignNameLowersCase()
    {
        $this->item = new MenuItem('Item 1');

        $this->assertEquals($this->item->name(), 'item 1');
    }

    //--------------------------------------------------------------------
    
    public function testAssignNameStripsTags()
    {
        $this->item = new MenuItem('<p>item 1</p>');

        $this->assertEquals($this->item->name(), 'item 1');
    }

    //--------------------------------------------------------------------

    public function testAutoAssignsTitle()
    {
        $this->item = new MenuItem('<p>item 1</p>');

        $this->assertEquals($this->item->title(), 'Item 1');
    }

    //--------------------------------------------------------------------

    public function testCanAssignLink()
    {
        $this->item = new MenuItem('item 1', 'Item One', 'mylink');

        $this->assertEquals($this->item->link(), 'mylink');
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Children
    //--------------------------------------------------------------------

    public function testAddChildWorks()
    {
        $this->item = new MenuItem('item 1');

        $this->item->addChild( new MenuItem('child 1') );

        $children = $this->item->children();

        $this->assertTrue(count($children) === 1);
        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $children[0]);
        $this->assertEquals($children[0]->name(), 'child 1');
    }

    //--------------------------------------------------------------------

    public function testAddChildReturnsSelf()
    {
        $this->item = new MenuItem('item 1');

        $return = $this->item->addChild( new MenuItem('child 1') );

        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $return);
        $this->assertEquals($return->name(), 'item 1');
    }

    //--------------------------------------------------------------------

    public function testChildNamedReturnsCorrectChild()
    {
        $this->item = new MenuItem('item 1');

        $this->item->addChild( new MenuItem('child 1') )
                   ->addChild( new MenuItem('child 2') )
                   ->addChild( new MenuItem('child 3') );

        $child = $this->item->childNamed('child 2');

        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $child);
        $this->assertEquals($child->name(), 'child 2');
    }

    //--------------------------------------------------------------------

    public function testChildNamedReturnsNullOnNoChildFound()
    {
        $this->item = new MenuItem('item 1');

        $this->item->addChild( new MenuItem('child 1') )
                   ->addChild( new MenuItem('child 2') )
                   ->addChild( new MenuItem('child 3') );

        $child = $this->item->childNamed('child 4');

        $this->assertTrue(is_null($child) );
    }

    //--------------------------------------------------------------------

    public function testHasChildrenReturnsFalseWhenNoChildrenExist()
    {
        $this->item = new MenuItem('item 1');

        $this->assertFalse($this->item->hasChildren());
    }

    //--------------------------------------------------------------------

    public function testHasChildrenReturnsTrueWhenChildrenExist()
    {
        $this->item = new MenuItem('item 1');

        $this->item->addChild( new MenuItem('child 1') );

        $this->assertTrue($this->item->hasChildren());
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Attributes
    //--------------------------------------------------------------------

    public function testSetAttributeReturnsSelf()
    {
        $this->item = new MenuItem('item 1');

        $return = $this->item->setAttribute('id', 'myid');

        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $return);
    }

    //--------------------------------------------------------------------

    public function testSetAttributeOverwritesExistingValues()
    {
        $this->item = new MenuItem('item 1');

        $this->item->setAttribute('id', 'first');
        $return = $this->item->setAttribute('id', 'second');

        $this->assertEquals('second', $this->item->attribute('id'));
        $this->assertEquals(1, count($this->item->attributes()));
    }

    //--------------------------------------------------------------------

    public function testSetAttributeReturnsFalseWhenInvalidNameType()
    {
        $this->item = new MenuItem('item 1');

        $this->assertFalse($this->item->setAttribute(['class'], 'value'));
    }

    //--------------------------------------------------------------------

    public function testMergeAttributeCreatesArrayOfValues()
    {
        $this->item = new MenuItem('item 1');

        $this->item->setAttribute('class', 'class 1');
        $this->item->mergeAttribute('class', 'class 2');

        $attributes = $this->item->attributes();

        $this->assertEquals(['class 1', 'class 2'], $attributes['class']);
    }

    //--------------------------------------------------------------------

    public function testMergeAttributeReturnsFalseWhenInvalidNameType()
    {
        $this->item = new MenuItem('item 1');

        $this->assertFalse($this->item->mergeAttribute(['class'], 'value'));
    }

    //--------------------------------------------------------------------

    public function testMergeAttributeReturnsSelf()
    {
        $this->item = new MenuItem('item 1');

        $this->item->setAttribute('class', 'class 1');
        $return = $this->item->mergeAttribute('class', 'class 2');

        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $return);
    }

    //--------------------------------------------------------------------

    public function testAttributeReturnsNullWhenNotSet()
    {
        $this->item = new MenuItem('item 1');

        $this->assertNull($this->item->attribute('class'));
    }

    //--------------------------------------------------------------------

    public function testAttributeReturnsCorrectValue()
    {
        $this->item = new MenuItem('item 1');

        $this->item->setAttribute('class', 'class 1');

        $this->assertEquals('class 1', $this->item->attribute('class'));
    }

    //--------------------------------------------------------------------

    public function testAttributeReturnsFalseWhenInvalidNameType()
    {
        $this->item = new MenuItem('item 1');

        $this->item->setAttribute('class', 'class 1');

        $this->assertFalse($this->item->attribute(['class']));
    }

    //--------------------------------------------------------------------

    public function testUnsetAttributeWorksWhenExists()
    {
        $this->item = new MenuItem('item 1');

        $this->item->setAttribute('class', 'class 1');
        $this->item->unsetAttribute('class');

        $this->assertNull($this->item->attribute('class'));
    }

    //--------------------------------------------------------------------

    public function testUnsetAttributeWorksWhenNotExists()
    {
        $this->item = new MenuItem('item 1');

        $this->item->unsetAttribute('class');

        $this->assertNull($this->item->attribute('class'));
    }

    //--------------------------------------------------------------------

    public function testUnsetAttributeReturnsSelf()
    {
        $this->item = new MenuItem('item 1');

        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $this->item->unsetAttribute('class'));
    }

    //--------------------------------------------------------------------
}