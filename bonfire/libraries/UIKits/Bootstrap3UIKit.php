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
     * Creates a row wrapper of HTML. We would have simple returned the
     * the class for it, but some frameworks use a completely different
     * approach to rows and columns than the reference Bootstrap and Foundation.
     *
     * @param array $options
     * @return mixed
     */
    public function row($options=[], \Closure $c)
    {
        $classes = $this->buildClassString('row', $options, true);

        $id = $this->buildIdFromOptions($options);

        $output = "<div {$classes}>";

        $output .= $this->runClosure($c);

        $output .= "</div>";

        return $output;
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
    public function column($options=[], \Closure $c)
    {
        // Build our classes
        $classes = '';

        foreach ($options['sizes'] as $size => $value)
        {
            switch ($size)
            {
                case 's':
                    $classes .= ' col-xs-'. $value;
                    break;
                case 'm':
                    $classes .= ' col-sm-'. $value;
                    break;
                case 'l':
                    $classes .= ' col-md-'. $value;
                    break;
                case 'xl':
                    $classes .= ' col-lg-'. $value;
                    break;
                case 's-offset':
                    $classes .= ' col-xs-offset-'. $value;
                    break;
                case 'm-offset':
                    $classes .= ' col-sm-offset-'. $value;
                    break;
                case 'l-offset':
                    $classes .= ' col-md-offset-'. $value;
                    break;
                case 'xl-offset':
                    $classes .= ' col-lg-offset-'. $value;
                    break;
            }
        }

        $classes = $this->buildClassString($classes, $options, true);

        $id = $this->buildIdFromOptions($options);

        $output = "<div {$classes} {$id}>";

        $output .= $this->runClosure($c);

        $output .= "</div>";

        return $output;
    }

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
    public function navbar($options=[], \Closure $c)
    {
        $output = '';

        /*
         * Open the navbar
         */
        $classes = "navbar navbar-default ";

        foreach ($options as $option)
        {
            switch ($option)
            {
                case 'sticky-top':
                    $classes .= " navbar-static-top";
                    break;
                case 'fixed':
                    $classes .= " navbar-fixed-top";
                    break;
                case 'inverse':
                    $classes .= " navbar-inverse";
            }
        }

        $output .= '<nav class="'. $classes .'" role="navigation">
  <div class="container-fluid">';

        /*
         * Do any user content inside the bar
         */
        $output .= $this->runClosure($c);

        /*
         * Close out the navbar
         */
        $output .= '</div></nav>';

        return $output;
    }

    //--------------------------------------------------------------------

    /**
     * Builds the HTML for the Title portion of the navbar. This typically
     * includes the code for the hamburger menu on small resolutions.
     *
     * @param        $title
     * @param string $url
     * @return string
     */
    public function navbarTitle($title, $url='#')
    {
        return '<div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="'. $url .'">'. $title .'</a>
    </div>';
    }

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
    public function navbarRight($options=[], \Closure $c)
    {
        // Class
        $classes = $this->buildClassString('nav navbar-nav navbar-right', $options);

        // ID
        $id = $this->buildIdFromOptions($options);
        
        $output = "<ul class='{$classes}' {$id}>\n";

        $output .= $this->runClosure($c);

        $output .= "</ul>\n";

        return $output;
    }
    
    //--------------------------------------------------------------------

    public function nav()
    {

    }

    //--------------------------------------------------------------------


    /**
     * Creates a single list item for use within a nav section.
     *
     * @param       $title
     * @param       $url
     * @param array $options
     * @return string
     */
    public function navItem($title, $url, $options=[], $active=false)
    {
        $options['active'] = $active;

        $classes = $this->buildClassString('', $options, true);

        $id = $this->buildIdFromOptions($options);

        return "\t<li {$classes} {$id}><a href='{$url}'>{$title}</a></li>";
    }
    
    //--------------------------------------------------------------------

    /**
     * Builds the shell of a Dropdown button for use within a nav area.
     *
     * @param          $title
     * @param array    $options
     * @param callable $c
     */
    public function navDropdown($title,$options=[], \Closure $c)
    {
        $classes = $this->buildClassString('dropdown', $options, true);

        $id = $this->buildIdFromOptions($options);

        $output = "\t<li {$classes} {$id}>
        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>{$title} <span class='caret'></span></a>
        <ul class='dropdown-menu' role='menu'>";

        $output .= $this->runClosure($c);

        $output .= "\t</ul></li>";

        return $output;
    }

    //--------------------------------------------------------------------

    /**
     * Creates a divider for use within a nav list.
     *
     * @return string
     */
    public function navDivider()
    {
        return '<li class="divider"></li>';
    }

    //--------------------------------------------------------------------




    //--------------------------------------------------------------------
    // Tables
    //--------------------------------------------------------------------

    public function table()
    {
        return 'table';
    }

    //--------------------------------------------------------------------

    /**
     * Helper method to run a Closure and collect the output of it.
     *
     * @param callable $c
     * @return string
     */
    protected function runClosure(\Closure $c)
    {
        if (! is_callable($c)) return '';

        ob_start();
        $c();
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    //--------------------------------------------------------------------

    /**
     * Combines an initial classes string with a 'class' item that
     * might be available within the options array.
     *
     * If 'buildEntireString' is TRUE will return the string with the 'class=""' portion.
     * Otherwise, just returns the raw classes.
     *
     * @param string $initial
     * @param array $options
     * @return array
     */
    protected function buildClassString($initial, $options, $buildEntireString=false)
    {
        $classes = explode(' ', $initial);

        if (isset($options['class']))
        {
            $classes = array_merge($classes, explode(' ', $options['class']));
        }

        if (isset($options['active']) && $options['active'] == true)
        {
            $classes[] = 'active';
        }

        $classes = implode(' ', $classes);

        return $buildEntireString ? "class='{$classes}'" : $classes;
    }
    //--------------------------------------------------------------------

    /**
     * Checks the options array for an ID and returns the entire string.
     *
     * Example Return:
     *      id='MyID'
     *
     * @param $options
     * @return string
     */
    protected function buildIdFromOptions($options)
    {
        return isset($options['id']) ? "id='{$options['id']}'" : '';
    }

    //--------------------------------------------------------------------


}