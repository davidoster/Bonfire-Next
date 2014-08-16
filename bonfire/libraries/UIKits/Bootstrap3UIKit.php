<?php

namespace Bonfire\Libaries\UIKits;

use Bonfire\Interfaces\UIInterface;

/**
 * Class Bootstrap3UIKit
 *
 * Provides a UIKit designed to work with Bootstrap 3.2.
 */
class Bootstrap3UIKit implements UIInterface {

    public function name()
    {
        return 'Bootstrap3UIKit';
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
        return 'row';
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
                    $out .= ' col-xs-'. $value;
                    break;
                case 'm':
                    $out .= ' col-sm-'. $value;
                    break;
                case 'l':
                    $out .= ' col-md-'. $value;
                    break;
                case 'xl':
                    $out .= ' col-lg-'. $value;
                    break;
                case 's-offset':
                    $out .= ' col-xs-offset-'. $value;
                    break;
                case 'm-offset':
                    $out .= ' col-sm-offset-'. $value;
                    break;
                case 'l-offset':
                    $out .= ' col-md-offset-'. $value;
                    break;
                case 'xl-offset':
                    $out .= ' col-lg-offset-'. $value;
                    break;
            }
        }

        return $out;
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