<?php

namespace Bonfire;

class Modules
{

    /**
     * @var String Path for Bonfire's "core modules"
     */
    protected static $bfModulesDir = 'bonfire/modules';

    /**
     * Holds data so that we don't have to hit
     * file system for checks again.
     *
     * @var array
     */
    protected static $cache = array();

    //--------------------------------------------------------------------

    /**
     * Returns a list of all modules in the system.
     *
     * @param bool $exclude_core Whether to exclude the Bonfire core modules or not
     *
     * @return array A list of all modules in the system.
     */
    public static function list_modules($exclude_core = false)
    {
        // Has it already been cached?
        if (isset(self::$cache['list_modules'][(int)$exclude_core])) {
            return self::$cache['list_modules'][(int)$exclude_core];
        }

        if (!function_exists('directory_map')) {
            get_instance()->load->helper('directory');
        }

        $map = array();

        $folders = Modules::folders();

        foreach ($folders as $folder) {
            // If excluding core modules, skip the core module folder
            if ($exclude_core && strpos($folder, self::$bfModulesDir) !== false) {
                continue;
            }

            $dirs = directory_map($folder, 1);
            if (!is_array($dirs)) {
                $dirs = array();
            }

            $map = array_merge($map, $dirs);
        }

        // Clean out any html or php files
        if ($count = count($map)) {
            for ($i = 0; $i < $count; $i ++) {
                if (strpos($map[$i], '.html') !== false || strpos($map[$i], '.php') !== false) {
                    unset($map[$i]);
                }
            }
        }

        // Cache it
        if (!isset(self::$cache['list_modules'])) {
            self::$cache['list_modules'] = array();
        }
        self::$cache['list_modules'][(int)$exclude_core] = $map;

        return $map;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the path to the module and it's specified folder.
     *
     * @param $module string The name of the module (must match the folder name)
     * @param $folder string The folder name to search for. (Optional)
     *
     * @return string The path, relative to the front controller, or false if the folder was not found
     */
    public static function path($module = null, $folder = null)
    {
        foreach (Modules::folders() as $module_folder) {
            if (is_dir($module_folder . $module)) {
                if (!empty($folder) && is_dir("{$module_folder}{$module}/{$folder}")) {
                    return realpath("{$module_folder}{$module}/{$folder}");
                }

                return realpath($module_folder . $module) . '/';
            }
        }

        return false;
    }

    /**
     * Convenience method to return the locations where modules can be found.
     *
     * @return array The config settings array for modules_locations.
     */
    public static function folders()
    {
        // @todo Modify MX/Modules to actually be able to find the modules locations config array

        return array(
            APPPATH . 'modules/',
            // application/modules
            BFPATH . 'modules/'
            // bonfire/modules
        );
    }
    //--------------------------------------------------------------------

}