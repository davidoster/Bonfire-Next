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
     * @throws RuntimeException
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
                throw new RuntimeException('You must add the docs folder that you wish to find docs from.');
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

        try {
            $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><div>' . $content .'</div>');
        }
        catch (Exception $e)
        {
            // SimpleXML barfed on us, so send back the un-modified content
            return $content;
        }

        // Prepare some things and cleanup others
        $groups      = array_keys($this->doc_folders);
        $site_url    = rtrim($site_url, '/') .'/';
        $current_url = rtrim($current_url, '#/');

        // Try to determine the current_url if one isn't set.
        if (empty($this->current_folder))
        {
            $this->current_folder = $this->detectCurrentFolder($current_url, $groups);
        }

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

            // Remove any trailing # signs
            $href = rtrim($href, '# ');

            // If the href starts with #, then attach the current_url to it
            if ($href != '' && substr_compare($href, '#', 0, 1) === 0)
            {
                $link['href'] = $current_url . $href;

                continue;
            }

            // If it's a full external path, go on...
            if ( (strpos($href, 'http://') !== false || strpos($href, 'https://') !== false) && strpos($href, $site_url) === false)
            {
                $link['target'] = "_blank";
                continue;
            }

            // If it's a full local path, get rid of it.
            if (strpos($href, $site_url) !== false)
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
                $href = $site_url .'docs/' . ltrim($href, '/ ');
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

    /**
     * Given the contents to render, will build a list of links for the sidebar
     * out of the headings in the file.
     *
     * Note: Will ONLY use h2 and h3 to build the links from.
     *
     * Note: The $content passed in WILL be modified by adding named anchors
     * that match up with the locations.
     *
     * @param string $content The HTML to analyse for headings.
     * @return string
     */
    public function buildDocumentMap (&$content)
    {
        if (empty($content))
        {
            return $content;
        }

        // If $content already has a wrapping <div> and </div> tags, remove them,
        // since we'll replace them just below.
        if (strpos($content, '<div>') === 0)
        {
            $content = substr($content, 5);

            // Trailing div also?
            if (substr($content, -6) == '</div>')
            {
                $content = substr($content, 0, -6);
            }
        }


        try {
            $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><div>' . $content .'</div>');
        }
        catch (Exception $e)
        {
            // SimpleXML barfed on us, so send back the un-modified content
            return [];
        }

        $map = [];

        // Holds the current h2 we're processing
        $current_obj = [];

        $i = 0;

        foreach ($xml->children() as $type => $line )
        {
            $i++;

            // Make sure that our current object is
            // stored and reset.
            if ($type == 'h1' || $type == 'h2')
            {
                if (count($current_obj))
                {
                    $map[] = $current_obj;
                    $current_obj = [];
                }
            }

            if ($type == 'h2')
            {
                $name = (string)$line;
                $link = strtolower( str_replace(' ', '_', (string)$line) );

                $current_obj['name'] = $name;
                $current_obj['link'] = '#'. $link;
                $current_obj['items'] = [];

                // Insert a named anchor into the $content
                $anchor = '<a name="'. $link .'" id="'. $link .'" ></a>';

                $search = "<h2>{$name}</h2>";

                $content = str_replace($search, $anchor . $search, $content);
            }

            else if ($type == 'h3')
            {
                // Make sure we have some place to store the items.
                if (! isset($current_obj['items']))
                {
                    $current_obj['items'] = [];
                }

                $link = strtolower( str_replace(' ', '_', (string)$line) );
                $name = (string)$line;

                $current_obj['items'][] = [
                    'name'  => $name,
                    'link'  => '#'. $link
                ];

                // Insert a named anchor into the $content
                $anchor = '<a name="'. $link .'" id="'. $link .'" ></a>';

                $search = "<h3>{$name}</h3>";

                $content = str_replace($search, $anchor . $search, $content);
            }

            // Is this the last element? Then close out our current object.
            if (count($xml) == $i)
            {
                if (count($current_obj))
                {
                    $map[] = $current_obj;
                }
            }
        }

        return $map;
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Table of Contents methods
    //--------------------------------------------------------------------

    /**
     * Retrieves the list of files in a folder and preps the name and filename
     * so it's ready for creating the HTML.
     *
     * @param  String $folder The path to the folder to retrieve.
     *
     * @return Array  An associative array @see parse_ini_file for format
     * details.
     */
    public function buildTOC ($folder)
    {
        // If the toc file exists in the folder, use it to build the links.
        if (is_file("{$folder}/_toc.ini"))
        {
            $toc = parse_ini_file("{$folder}/_toc.ini", TRUE);
            return $this->columnizeTOC( $toc );
        }

        // If the toc file does not exist, build the links by listing the files
        // in the directory (and any sub-directories)
        $this->load->helper('directory');
        $map = directory_map($folder);

        // If directory_map can not open the directory or find any files inside
        // the directory, return an empty array.
        if (empty($map))
        {
            return array();
        }

        // If these docs are located in the /application/docs or /bonfire/docs
        // directory, just use $this->current_group for the root.
        // Module docs need $this->current_group and $type.
        $tocRoot = $this->current_group;
        if ($this->current_group != strtolower($type))
        {
            $tocRoot .= '/' . strtolower($type);
        }

        $toc = array();
        foreach ($map as $new_folder => $files)
        {
            // If $files isn't an array, then make it one so that all situations
            // may be dealt with cleanly.
            if (! is_array($files))
            {
                $files = array($files);
            }

            foreach ($files as $file)
            {
                if (in_array($file, $this->ignoreFiles))
                {
                    continue;
                }

                // The title for the index is the passed $type. Otherwise,
                // build the title from the file's name.
                if (strpos($file, 'index') === FALSE)
                {
                    $title = str_replace($this->docsExt, '', $file);
                    $title = str_replace('_', ' ', $title);
                    $title = ucwords($title);

                    $toc["{$tocRoot}/{$file}"] = $title;
                }
                else
                {
                    $toc[$tocRoot] = $type;
                }
            }
        }

        $toc = $this->columnizeTOC($toc);

        return $toc;
    }

    //--------------------------------------------------------------------

    /**
     * Sorts the passed TOC array into columns of as close to equal length
     * as we can get it.
     *
     * @param $toc
     * @return array
     */
    protected function columnizeTOC($toc)
    {
        $section_count = count($toc);

        // First - determine the size of each 'section'.
        $sizes = [];

        foreach ($toc as $section => $chapters)
        {
            $sizes[] = count($chapters);
        }

        $column_avg = (int)round( array_sum($sizes) / $section_count);

        // Split things into 4 columns of approximately equal size.
        // If we only have 4 columns (or less), then make sure to
        // deal with that also.
        $columns = [];

        $current_column = 0;
        $current_column_count = 0;
        $keys = array_keys($toc);

        for ($i=0; $i <= $section_count; $i++)
        {
            if (! isset($keys[$i]))
            {
                continue;
            }

            $section = array_shift($toc);

            // Can we stay in this column?
            if ($current_column_count <= $column_avg && $section_count > 4)
            {
                // Don't forget to account for the heading also.
                $current_column_count += count($section) +1;
            }
            // Else - move to new column...
            else {
                $current_column_count = 0;
                $current_column++;
            }

            $columns[ $current_column ][ $keys[$i] ] = $section;
        }

        return $columns;
    }

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

    //--------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------

    /**
     * Analyzes the passed in current url string and checks against
     * a list of groups to determine what the current group is.
     *
     * @param $current_url
     * @param $groups
     * @return string
     */
    public function detectCurrentFolder ($current_url, $groups=[])
    {
        if (! is_array($groups))
        {
            return null;
        }

        $segments = explode('/', $current_url);

        // We start from the back of the array since
        // that's most likely to be close to the end.
        $segments = array_reverse($segments);

        foreach ($segments as $segment)
        {
            foreach ($groups as $group)
            {
                if (strtolower($group) == strtolower($segment))
                {
                    return $group;
                }
            }
        }

        // Nothing found?
        return null;
    }

    //--------------------------------------------------------------------
}