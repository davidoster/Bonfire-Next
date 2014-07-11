<?php

/*
|--------------------------------------------------------------------
| DI Classes
|--------------------------------------------------------------------
| Any classes that are instantiated into the Base_Controller's DI container
| must be listed here so they can be easily swapped out per application.
*/
$config['di.templateEngine'] = 'Bonfire\PlatesTemplate';


//--------------------------------------------------------------------
// ACTIVITIES
//--------------------------------------------------------------------

/*
	If TRUE, will log activities to the database using the activity_model's
	log_activity. If this is FALSE, you can remove the Activity module
	without repercussions.
 */
$config['enable_activity_logging'] = TRUE;

$config['show_profiler'] = TRUE;

/*
|---------------------------------------------------------------------
| THEMES
|---------------------------------------------------------------------
| An array of folders to look in for themes. There must be at least
| one folder path at all times, to serve as the fall-back for when
| a theme isn't found. Paths are relative to the FCPATH.
*/
$config['template.theme_paths'] = [
    'admin' => APPPATH .'../themes/admin',
    'docs'  => APPPATH .'../themes/docs'
];

//--------------------------------------------------------------------
// Migrations
//--------------------------------------------------------------------
$config['migrate.auto_core']	= FALSE;
$config['migrate.auto_app']		= FALSE;