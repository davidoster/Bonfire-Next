<?php

class DocBuilder {

    protected $docs_ext = '.md';

    protected $ignore_files = ['_404.md'];

    protected $doc_folders = [];

    /**
     * Stores the current folder alias,
     * once the file has been found.
     *
     * @var null
     */
    protected $current_folder = null;

    protected $table_classes = 'table table-hover';

    //--------------------------------------------------------------------

    /**
     * Does the actual work of reading in and parsing the help file.
     * If a folder Nickname (see addDocFolder() ) is passed as the second parameter,
     * it will limit it's search to that single folder. If nothing is passed, it will
     * search through all of the folders in the order they were given to the library,
     * until it finds the first one.
     *
     * @param  string $path               The 'path' of the file (relative to the docs folder. Usually from the URI)
     * @param  string $restrictToFolder   (Optional) The nickname of one of the folders to restrict the search to.
     *
     * @return string
     */
    public function readPage ($path, $restrictToFolder=null)
    {
        // Clean up our path
        $path = trim($path, '/ ');

        $content = $this->locateAndReadFile($path, $restrictToFolder);

        $content = $this->parse($content);

        return $content;
    }

    //--------------------------------------------------------------------

    /**
     * Locates the file on disk and reads the contents into a single string.
     *
     * If a folder Nickname (see addDocFolder() ) is passed as the second parameter,
     * it will limit it's search to that single folder. If nothing is passed, it will
     * search through all of the folders in the order they were given to the library,
     * until it finds the first one.
     *
     * @param  string $path             The 'path' of the file (relative to the docs folder. Usually from the URI)
     * @param  string $restrictToFolder (Optional) The nickname of one of the folders to restrict the search to.
     *
     * @throws InvalidArgumentException
     * @return null|string
     */
    private function locateAndReadFile ($path, $restrictToFolder=null)
    {
        $folders = $this->doc_folders;

        if (! is_null($restrictToFolder))
        {
            // Make sure the folder exists
            if (! is_null($restrictToFolder) && ! isset($this->doc_folders[ $restrictToFolder ]))
            {
                throw new InvalidArgumentException('You must add the docs folder that you wish to find docs from.');
            }

            $folders = array( $this->doc_folders[$restrictToFolder] );
        }


        foreach ($folders as $alias => $folder)
        {
            if (file_exists($folder . $path . $this->docs_ext))
            {
                // Store the alias so we know which folder we're in.
                $this->current_folder = $alias;

                return file_get_contents($folder . $path . $this->docs_ext);
            }
        }

        return null;
    }

    //--------------------------------------------------------------------

    /**
     * Parses the contents. Currently runs through the Markdown Extended
     * parser to convert to HTML.
     *
     * @param $str
     * @return mixed
     */
    public function parse ($str)
    {
        require_once(BFPATH .'helpers/markdown_extended_helper.php');

        return MarkdownExtended($str);
    }

    //--------------------------------------------------------------------

    /**
     * Perform a few housekeeping tasks on a page, like rewriting URLs to full
     * URLs, not relative, ensuring they link correctly, etc.
     *
     * @param      $content
     * @param null $site_url
     * @param null $current_url
     * @return string   The post-processed HTML.
     */
    public function postProcess ($content, $site_url=null, $current_url=null)
    {
        if (empty($content))
        {
            return $content;
        }

        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?>' . $content);

        // Prepare some things and cleanup others
        $groups      = array_keys($this->doc_folders);
        $site_url    = rtrim($site_url, '/') .'/';
        $current_url = rtrim($current_url, '/');

        /*
         * Rewrite the URLs
         */
        foreach ($xml->xpath('//a') as $link)
        {
            // Grab the href value.
            $href = $link->attributes()->href;

            // If the href is null, it's probably a named anchor with no content.
            if (! $href)
            {
                // Make sure it has an href, else the XML will not close this
                // tag correctly.
                $link['href'] = ' ';

                // A title is needed so the XML will be built correctly.
//                $link->title = '';

                continue;
            }

            // If the href starts with #, then attach the current_url to it
            if ($href != '' && substr_compare($href, '#', 0, 1) === 0)
            {
                $link['href'] = $current_url . $href;

                continue;
            }

            // If it's a full local path, get rid of it.
            if (strpos($href, $site_url) === 0)
            {
                $href = str_replace($site_url, '', $href);
            }

            // Strip out some unnecessary items, just in case they're there.
            if (substr($href, 0, strlen('docs/')) == 'docs/')
            {
                $href = substr($href, strlen('docs/'));
            }

            // This includes 'bonfire/' if it was missed during the conversion.
            if (substr($href, 0, strlen('bonfire/')) == 'bonfire/')
            {
                $href = substr($href, strlen('bonfire/'));
            }

            // If another 'group' is not already defined at the head of the link
            // then add the current group to it.
            $group_found = false;

            foreach ($groups as $group)
            {
                if (strpos($href, $group) === 0)
                {
                    $group_found = true;
                }
            }

            if (! $group_found)
            {
                $href = $this->current_folder . '/' . $href;
            }

            // Convert to full site_url
            if (strpos($href, 'http') !== 0)
            {
                $href = $site_url .'docs' . $href;
            }

            // Save the corrected href
            $link['href'] = $href;
        }

        $content = $xml->asXML();
        $content = trim(str_replace('<?xml version="1.0" standalone="yes"?>', '', $content));

        // Clean up and style the tables
        $content = str_replace('<table>', '<table class="'. $this->table_classes .'">', $content);

        return $content;
    }
    //--------------------------------------------------------------------

    /**
     * Allows users to define the classes that are attached to
     * generated tables.
     *
     * @param null $classes
     * @return $this
     */
    public function setTableClasses ($classes=null)
    {
        $this->table_classes = $classes;

        return $this;
    }

    //--------------------------------------------------------------------

//    public function readPage ($segments = [])
//    {
//        $content     = NULL;
//        $defaultType = $this->docsTypeApp;
//
//        $ci =& get_instance();
//
//        // Strip the controller name
//        if ($segments[1] == $ci->router->fetch_class())
//        {
//            array_shift($segments);
//        }
//
//        // Is this core, app, or module?
//        $type = array_shift($segments);
//        if (empty($type))
//        {
//            $type = $defaultType;
//        }
//
//        // For now, assume Markdown files are the only allowed format, with an
//        // extension of '.md'
//        if (count($segments))
//        {
//            $file = implode('/', $segments) . $this->docsExt;
//        }
//        else
//        {
//            $file = 'index' . $this->docsExt;
//            if ($type != $this->docsTypeMod
//                && ! is_file(APPPATH . $this->docsDir . '/' . $file)
//            )
//            {
//                $type = $this->docsTypeBf;
//            }
//        }
//
//        // First try to load from Activities or Bonfire.
//        switch ($type)
//        {
//            case $this->docsTypeBf:
//                $content = is_file(BFPATH . $this->docsDir . '/' . $file) ?
//                    file_get_contents(BFPATH . $this->docsDir . '/' . $file) : '';
//                break;
//
//            case $this->docsTypeApp:
//                $content = is_file(APPPATH . $this->docsDir . '/' . $file) ?
//                    file_get_contents(APPPATH . $this->docsDir . '/' . $file) : '';
//                break;
//        }
//
//        // If the file wasn't found, try to find a module with the content.
//        if (empty($content))
//        {
//            $module = array_shift($segments);
//
//            // If anything's left on $segments, it's probably a filename
//            $fileName = count($segments) ? array_shift($segments) : 'index';
//            $fileName .= '.md';
//
//            // Developer docs for modules should be found under the
//            // '{module}/docs/developer' path.
//            $addPath = $type == $this->docsTypeBf ? '/' . $this->docsTypeBf . '/' : '/';
//
//            // This time, try it based on the name of the segment brought in
//            list($full_path, $file) = Modules::find($fileName, $module, $this->docsDir . $addPath);
//            if ($full_path)
//            {
//                $content = file_get_contents($full_path . $file);
//            }
//        }
//
//        // If the content is still empty, load the application/docs/404 file
//        // so that we have a customizable not found file.
//        if (empty($content))
//        {
//            $content = is_file(APPPATH . $this->docsDir . '/_404.md') ?
//                file_get_contents(APPPATH . $this->docsDir . '/_404.md') : '';
//        }
//
//        // Parse the file
//        $ci->load->helper('markdown_extended');
//        $content = MarkdownExtended($content);
//
//        return trim($content);
//    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Folder Methods
    //--------------------------------------------------------------------

    /**
     * Returns the current docFolders array.
     *
     * @return array
     */
    public function docFolders ()
    {
        return $this->doc_folders;
    }

    //--------------------------------------------------------------------

    /**
     * Registers a path to be used when searching for documentation files.
     *
     * @param $name     A nickname to reference it by later.
     * @param $path     The server path to the folder.
     * @return $this
     */
    public function addDocFolder ($name, $path)
    {
        // Standardize the path
        $path = realpath($path) .'/';

        // realpath will return FALSE if the path doesn't exist
        // or the script doesn't have access to it.
        if (! $path || $path == '/')
        {
            return $this;
        }

        $name = strtolower($name);

        $this->doc_folders[ $name ] = $path;

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Removes a folder from the folders we scan for documentation files
     * within.
     *
     * @param $name
     * @return $this
     */
    public function removeDocFolder ($name)
    {
        $name = strtolower($name);

        if (isset($this->doc_folders[$name]))
        {
            unset($this->doc_folders[$name]);
        }

        return $this;
    }

    //--------------------------------------------------------------------

}