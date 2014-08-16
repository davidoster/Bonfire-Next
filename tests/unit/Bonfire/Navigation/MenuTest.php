<?php
namespace Bonfire\Libraries\Navigation;


class MenuTest extends \Codeception\TestCase\Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    protected $menu;

    protected function _before()
    {
        $this->menu = new Menu('admin');
    }

    protected function _after()
    {
    }

    // tests
    public function testIsLoaded()
    {
        $this->assertInstanceOf('Bonfire\Libraries\Navigation\Menu', $this->menu);
    }

    //--------------------------------------------------------------------

    public function testNameReturnsName()
    {
        $this->assertEquals('admin', $this->menu->name());
    }

    //--------------------------------------------------------------------

    public function testNameIsLowerCased()
    {
        $this->menu = new Menu('ADMIN');

        $this->assertEquals('admin', $this->menu->name());
    }

    //--------------------------------------------------------------------

    public function testNameStripsTags()
    {
        $this->menu = new Menu('<p>Admin</p>');

        $this->assertEquals('admin', $this->menu->name());
    }

    //--------------------------------------------------------------------



    public function testAddItemWorks()
    {
        $this->menu->addItem(new MenuItem('item 1'));

        $items = $this->menu->items();

        $this->assertTrue(count($items) === 1);
        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $items[0]);
        $this->assertEquals($items[0]->name(), 'item 1');
    }

    //--------------------------------------------------------------------

    public function testAddItemReturnsSelf()
    {
        $return = $this->menu->addItem(new MenuItem('item 1'));

        $this->assertInstanceOf('Bonfire\Libraries\Navigation\Menu', $return);
    }

    //--------------------------------------------------------------------

    public function testItemNamedReturnsCorrectItem()
    {
        $this->menu->addItem(new MenuItem('item 1'));
        $this->menu->addItem(new MenuItem('item 2'));
        $this->menu->addItem(new MenuItem('item 3'));

        $this->assertEquals('item 2', $this->menu->itemNamed('item 2')->name());
    }

    //--------------------------------------------------------------------

    public function testItemNamedReturnsNullOnNoItemFound()
    {
        $this->menu->addItem(new MenuItem('item 1'));
        $this->menu->addItem(new MenuItem('item 2'));
        $this->menu->addItem(new MenuItem('item 3'));

        $this->assertNull($this->menu->itemNamed('item 4'));
    }

    //--------------------------------------------------------------------

    public function testItemNamedReturnsNullWhenNoItemsExist()
    {
        $this->assertNull($this->menu->itemNamed('item 4'));
    }

    //--------------------------------------------------------------------

    public function testItemsReturnsEmptyArrayWhenNoItemsExist()
    {
        $this->assertEquals([], $this->menu->items());
    }

    //--------------------------------------------------------------------

    public function testHasItemsReturnsFalseWhenNoItemsExist()
    {
        $this->assertFalse($this->menu->hasItems());
    }

    //--------------------------------------------------------------------

    public function testHasItemsReturnsTrueWhenItemsExist()
    {
        $this->menu->addItem(new MenuItem('item 1'));

        $this->assertTrue($this->menu->hasItems());
    }

    //--------------------------------------------------------------------

    public function testAddChildAddsParentAlso()
    {
        $this->menu->addChild(new MenuItem('child 1'), 'item 1');

        $this->assertInstanceOf('Bonfire\Libraries\Navigation\MenuItem', $this->menu->itemNamed('item 1'));
    }

    //--------------------------------------------------------------------

    public function testAddChildReturnsSelf()
    {
        $return = $this->menu->addChild(new MenuItem('child 1'), 'item 1');

        $this->assertEquals('admin', $return->name());
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Sorting
    //--------------------------------------------------------------------

    public function testSortOrderAsc()
    {
        $this->menu->addItem(new MenuItem('item b'));
        $this->menu->addItem(new MenuItem('item a'));

        $items = $this->menu->sortBy('order', 'asc')
                            ->items();

        $this->assertEquals('item a', $items[0]->name());
    }

    //--------------------------------------------------------------------

    public function testSortOrderDesc()
    {
        $this->menu->addItem(new MenuItem('item b'));
        $this->menu->addItem(new MenuItem('item a'));

        $items = $this->menu->sortBy('order', 'desc')
                            ->items();

        $this->assertEquals('item b', $items[0]->name());
    }

    //--------------------------------------------------------------------

    public function testSortNameAsc()
    {
        $this->menu->addItem(new MenuItem('item b'));
        $this->menu->addItem(new MenuItem('item a'));

        $items = $this->menu->sortBy('name', 'asc')
                            ->items();

        $this->assertEquals('item a', $items[0]->name());
    }

    //--------------------------------------------------------------------

    public function testSortNameDesc()
    {
        $this->menu->addItem(new MenuItem('item b'));
        $this->menu->addItem(new MenuItem('item c'));
        $this->menu->addItem(new MenuItem('item a'));

        $items = $this->menu->sortBy('name', 'desc')
                            ->items();

        $this->assertEquals('item c', $items[0]->name());
    }

    //--------------------------------------------------------------------

    public function testSortTitleAsc()
    {
        $this->menu->addItem(new MenuItem('item b'));
        $this->menu->addItem(new MenuItem('item a'));

        $items = $this->menu->sortBy('title', 'asc')
                            ->items();

        $this->assertEquals('item a', $items[0]->name());
    }

    //--------------------------------------------------------------------

    public function testSortTitleDesc()
    {
        $this->menu->addItem(new MenuItem('item b'));
        $this->menu->addItem(new MenuItem('item c'));
        $this->menu->addItem(new MenuItem('item a'));

        $items = $this->menu->sortBy('title', 'desc')
                            ->items();

        $this->assertEquals('item c', $items[0]->name());
    }

    //--------------------------------------------------------------------


}