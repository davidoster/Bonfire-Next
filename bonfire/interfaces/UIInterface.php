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
    public function row($options=[], \Closure $c);

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
    public function column($options=[], \Closure $c);

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Navigation
    //--------------------------------------------------------------------

    /**
     * Generates the container code for a navbar, typically used along the
     * top of a page.
     *
     * @param array    $options
     * @param callable $c
     * @return string
     */
    public function navbar($options=[], \Closure $c);

    //--------------------------------------------------------------------

    /**
     * Builds the HTML for the Title portion of the navbar. This typically
     * includes the code for the hamburger menu on small resolutions.
     *
     * @param        $title
     * @param string $url
     * @return string
     */
    public function navbarTitle($title, $url="#");

    //--------------------------------------------------------------------

    /**
     * Creates a UL meant to pull to the right within the navbar.
     *
     * Available options:
     *      'class'     - An additional class to add
     *
     * @param array    $options
     * @param callable $c
     * @return string
     */
    public function navbarRight($options=[], \Closure $c);

    //--------------------------------------------------------------------

    public function nav();

    //--------------------------------------------------------------------

    /**
     * Creates a single list item for use within a nav section.
     *
     * @param       $title
     * @param       $url
     * @param array $options
     * @return string
     */
    public function navItem($title, $url, $options=[], $active=false);

    //--------------------------------------------------------------------

    /**
     * Builds the shell of a Dropdown button for use within a nav area.
     *
     * @param          $title
     * @param array    $options
     * @param callable $c
     */
    public function navDropdown($title,$options=[], \Closure $c);

    //--------------------------------------------------------------------

    /**
     * Creates a divider for use within a nav list.
     *
     * @return string
     */
    public function navDivider();

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // Tables
    //--------------------------------------------------------------------

    public function table();

    //--------------------------------------------------------------------

}