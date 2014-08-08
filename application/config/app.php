<?php

/*
|--------------------------------------------------------------------
| DI Classes
|--------------------------------------------------------------------
| Any classes that are instantiated into the Base_Controller's DI container
| must be listed here so they can be easily swapped out per application.
*/
$config['di.templateEngine'] = 'Bonfire\Libraries\ViewTemplate';


//--------------------------------------------------------------------
// ACTIVITIES
//--------------------------------------------------------------------

/*
	If TRUE, will log activities to the database using the activity_model's
	log_activity. If this is FALSE, you can remove the Activity module
	without repercussions.
 */
$config['enable_activity_logging'] = TRUE;

$config['show_profiler'] = false;

/*
|---------------------------------------------------------------------
| THEMES
|---------------------------------------------------------------------
| An array of folders to look in for themes. There must be at least
| one folder path at all times, to serve as the fall-back for when
| a theme isn't found. Paths are relative to the FCPATH.
*/
$config['template.theme_paths'] = [
    'admin' => APPPATH .'views/themes/admin',
    'docs'  => APPPATH .'views/themes/docs'
];


/*
|---------------------------------------------------------------------
| VARIANTS
|---------------------------------------------------------------------
| Variants are different versions of the view files that can be used.
| These are used with system agents to serve up different versions of
| the view files based on the device type that is looking at the page.
|
| The key is the name the variant is referenced by.
| The value is the string the is added to the view name.
*/
$config['template.variants'] = [
    'phone' => '+phone',
    'table' => '+tablet'
];

/*
    If TRUE, The ThemedController (and children) will automatically
    attempt to determine whether the user is using a desktop,
    mobile phone, or tablet to browse the site. This is then set
    in the template engine so it will attempt to use variant files.
 */
$config['autodetect_variant'] = true;

//--------------------------------------------------------------------
// Migrations
//--------------------------------------------------------------------
$config['migrate.auto_core']	= FALSE;
$config['migrate.auto_app']		= FALSE;