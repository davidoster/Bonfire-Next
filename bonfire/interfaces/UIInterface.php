<?php

namespace Bonfire\Interfaces;

/**
 * Interface UIInterface
 *
 * Implements the basic requirements for common elements across
 * different CSS Frameworks. Allows our system to easily be
 * used with different frameworks to create common effects.
 * Should hopefully allow us to NOT have to change stuff out
 * in libraries and allow them to be used with different CSS
 * frameworks in the admin and frontend sections.
 *
 * @package Bonfire\Interfaces
 */
interface UIInterface {

    //--------------------------------------------------------------------
    // Grid System
    //--------------------------------------------------------------------
    // Deals only with the CSS classes themselves for rows, since most
    // systems use <div> for the container.

    /**
     * Return's the 'row' css class. For Bootstrap, it's "row", for
     * PureCSS it's, "pure-g", etc.
     *
     * @return mixed
     */
    public function row();

    //--------------------------------------------------------------------

    /**
     * Creates the CSS for a column in a grid.
     *
     * The attribute array is made up of key/value pairs with the
     * key being the size, and the value being the number of columns/offset
     * in a 12-column grid.
     *
     * Valid sizes - 'small', 'medium', 'large', 'xlarge'
     *               'small-offset', 'medium-offset', 'large-offset', 'xlarge-offset'
     *
     * @param array $attributes
     * @return mixed
     */
    public function column($attributes=[]);

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Navigation
    //--------------------------------------------------------------------

    public function nav();

    //--------------------------------------------------------------------

    /**
     * Creates navigation class for a vertical set of nav links.
     *
     * For Bootstrap, this would be "nav nav-stacked".
     * For Foundation it would be "side-nav"
     *
     * @param array $options
     * @return mixed
     */
    public function nav_sidebar();

    //--------------------------------------------------------------------

}