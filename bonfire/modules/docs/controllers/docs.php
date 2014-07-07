<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Docs extends Base_Controller
{
    protected $ignoreFiles = array('_404.md');

    protected $tocFile;

    protected $doc_folders = [];
    
    protected $current_group = null;

    protected $current_path = null;

    private $showAppDocs;

    private $showDevDocs;

    //--------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return \Docs
     */
    public function __construct ()
    {
        parent::__construct();

        $this->load->config('docs');
        $this->lang->load('docs');

        // Save our folders
        $this->doc_folders = config_item('docs.folders');

        list($this->current_group, $this->current_path) = $this->determineFromURL();

        // Is displaying docs permitted for this environment?
        if (config_item('docs.permitted_environments')
            && ! in_array(ENVIRONMENT, config_item('docs.permitted_environments'))
        )
        {
            Template::set_message(lang('docs_env_disabled'), 'error');
            redirect();
        }

        $this->showAppDocs = config_item('docs.show_app_docs');
        $this->showDevDocs = config_item('docs.show_dev_docs');
        $this->tocFile     = config_item('docs.toc_file') ? : '_toc.ini';

        // Make sure we can still get to the search method.
        if ($this->current_group == 'search')
        {
            $this->current_group = FALSE;
        }
        // Are we allowed to show developer docs in this environment?
        elseif ($this->current_group == 'developer'
                && ! $this->showDevDocs
                && ENVIRONMENT != 'development'
        )
        {
            if ($this->showAppDocs)
            {
                Template::set_message(lang('docs_not_allowed_dev'), 'warning');

                redirect('docs/application');
            }

            show_error(lang('docs_not_allowed'));
        }

        $this->template->setTheme('docs');

        $this->load->helper('form');

        $this->load->library('DocBuilder');
    }

    //--------------------------------------------------------------------

    /**
     * Display the list of documents available and the current document
     *
     * @return void
     */
    public function index ()
    {
        $data = array();

        // Make sure the builder knows where to look
        foreach ($this->doc_folders as $alias => $folder)
        {
            $this->docbuilder->addDocFolder($alias, $folder);
        }

        $content = $this->docbuilder->readPage($this->current_path, $this->current_group);
        $content = $this->docbuilder->postProcess($content, site_url(), current_url());

        $data['sidebar'] = $this->buildSidebar($content);
        $data['content'] = $content;

        $this->render($data);
    }

    //--------------------------------------------------------------------

    /**
     * Display search results and handles the search itself.
     *
     * @return void
     */
    public function search ()
    {
        $this->benchmark->mark('search_start');
        $this->load->library('docs/docsearch');

        $data = array();

        $terms = $this->input->post('search_terms');
        if ($terms)
        {
            $search_folders = array();
            if ($this->showAppDocs)
            {
                $search_folders[] = APPPATH . $this->docsDir;
            }

            if ($this->showDevDocs)
            {
                $search_folders[] = BFPATH . $this->docsDir;
            }

            $data['results'] = $this->docsearch->search($terms, $search_folders);
        }

        $this->benchmark->mark('search_end');

        $data['search_time']  = $this->benchmark->elapsed_time('search_start', 'search_end');
        $data['search_terms'] = $terms;

        $this->render($data);
    }

    //--------------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------------

    /**
     * Determines the current doc group and file path from the current URL.
     *
     * Returns an array with the group and file path in the 0 and 1 positions, respectively.
     *
     * @return array
     */
    private function determineFromURL ()
    {
        $return = [
            '',     // Group
            '',     // File Path
        ];

        $segments = $this->uri->segment_array();

        // Remove the 'docs' from the array
        // for now, we assume this is the first one
        // since that is how Bonfire is setup to show docs
        // @todo Make it so the path can be modified and this still works.
        array_shift($segments);

        // If nothing left, then assign the default group and redirect to
        // a page we can do something with...
        if (! count($segments))
        {
            redirect('docs/'. config_item('docs.default_group'));
        }

        // Do we have a group specified? Bonfire Docs requires that a group
        // be part of the URI so it should be the first element on the array.
        $return[0] = array_shift($segments);

        // If there's any more left, then join them together and they'll
        // form the path to the file. This will allow for subfolders with the
        // docs folder itself.
        $return[1] = count($segments) ? implode('/', $segments) : 'index';

        return $return;
    }

    //--------------------------------------------------------------------

    /**
     * Builds a TOC for the sidebar out of files found in the following folders:
     *      - application/docs
     *      - bonfire/docs
     *      - {module}/docs
     *
     * @param $content  The HTML generated for the page content.
     * @return string   The HTML for the sidebar.
     */
    private function buildSidebar (&$content)
    {
        $data = [];

        // Set the remaining data for the view
        $data['docsDir'] = 'docs/'. $this->current_group .'/';
        $data['docsExt'] = config_item('docs.extension');

        $data['docMap'] = $this->docbuilder->buildDocumentMap($content);

        return $this->docbuilder->postProcess($this->load->view('docs/_document_map', $data, TRUE), site_url(), current_url());
    }

    //--------------------------------------------------------------------

    /**
     * Retrieves the list of files in a folder and preps the name and filename
     * so it's ready for creating the HTML.
     *
     * @param  String $folder         The path to the folder to retrieve.
     * @param  Array  $ignoredFolders A list of sub-folders we should ignore.
     *
     * @return Array  An associative array @see parse_ini_file for format
     * details.
     */
    private function get_folder_files ($folder, $ignoredFolders = array())
    {
        // If the toc file exists in the folder, use it to build the links.
        if (is_file("{$folder}/{$this->tocFile}"))
        {
            return parse_ini_file("{$folder}/{$this->tocFile}", TRUE);
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
            // Is this a folder that should be ignored?
            if (is_string($new_folder) && in_array($new_folder, $ignoredFolders))
            {
                continue;
            }

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

        return $toc;
    }

    //--------------------------------------------------------------------

    /**
     * Checks all modules to see if they include docs and prepares their doc
     * information for use in the sidebar.
     *
     * @return array
     */
    private function get_module_docs ()
    {
        $docs_modules = array();
        foreach (\Bonfire\Modules::list_modules() as $module)
        {
            $ignored_folders = array();
            $path            = \Bonfire\Modules::path($module) . $this->docsDir;

            // If these are developer docs, add the folder to the path.
            if ($this->current_group == $this->docsTypeBf)
            {
                $path .= '/' . $this->docsTypeBf;
            }
            // For Application docs, ignore the 'developers' folder.
            else
            {
                $ignored_folders[] = $this->docsTypeBf;
            }

            if (is_dir($path))
            {
                $files = $this->get_folder_files($path, $module, $ignored_folders);
                if (is_array($files) && count($files))
                {
                    $docs_modules[$module] = $files;
                }
            }
        }

        return $docs_modules;
    }

    //--------------------------------------------------------------------



}