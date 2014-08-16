<?php

namespace Bonfire\Libaries\UIKits;

use Bonfire\Interfaces\UIInterface;

/**
 * Class Pure05UIKit
 *
 * Provides a UIKit designed to work with Pure CSS 0.5.
 *
 * NOTE that the grid system requires you to pull in the responsive grid CSS which ships separately.
 * <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
 *
 * Pure does NOT support offset grids so this feature is unavailable.
 */
class Pure05UIKit implements UIInterface {

    public function name()
    {
        return 'Pure05UIkit';
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Grid
    //--------------------------------------------------------------------

    /**
     * Return's the 'row' css class. For Bootstrap, it's "row", for
     * PureCSS it's, "pure-g", etc.
     *
     * @return mixed
     */
    public function row()
    {
        return 'pure-g';
    }

    //--------------------------------------------------------------------

    /**
     * Creates the CSS for a column in a grid.
     *
     * The attribute array is made up of key/value pairs with the
     * key being the size, and the value being the number of columns/offset
     * in a 12-column grid.
     *
     * Note that we currently DO NOT support offset columns.
     *
     * Valid sizes - 's', 'm', 'l', 'xl', 's-offset', 'm-offset', 'l-offset', 'xl-offset'
     *
     * Please note that that sizes are different than in Bootstrap. For example, for a 'xs'
     * column size in Bootstrap, you would use 's' here. 'sm' = 'm', etc.
     *
     * @param array $attributes
     * @return mixed
     */
    public function column($attributes=[])
    {
        $out = '';

        foreach ($attributes as $size => $value)
        {
            switch ($size)
            {
                case 's':
                    $out .= ' pure-u-sm-'. (2 *$value);
                    break;
                case 'm':
                    $out .= ' pure-u-md-'. (2 *$value);
                    break;
                case 'l':
                    $out .= ' pure-u-lg-'. (2 *$value);
                    break;
                case 'xl':
                    $out .= ' pure-u-xl-'. (2 *$value);
                    break;
            }
        }

        return $out .'-24';
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Navigation
    //--------------------------------------------------------------------

    public function nav()
    {
        return 'nav';
    }

    //--------------------------------------------------------------------

    public function nav_sidebar()
    {
        return "nav nav-pills nav-stacked";
    }

    //--------------------------------------------------------------------



}