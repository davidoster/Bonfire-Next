<?php

/*
|--------------------------------------------------------------------
| DI Classes
|--------------------------------------------------------------------
| Any classes that are instantiated into the Base_Controller's DI container
| must be listed here so they can be easily swapped out per application.
*/
$config['di.templateEngine'] = 'Bonfire\PlatesTemplate';
$config['di.assetEngine']    = 'Bonfire\AsseticAssets';


//--------------------------------------------------------------------
// ACTIVITIES
//--------------------------------------------------------------------

/*
	If TRUE, will log activities to the database using the activity_model's
	log_activity. If this is FALSE, you can remove the Activity module
	without repercussions.
 */
$config['enable_activity_logging'] = TRUE;

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



//---------------------------------------------------------------------
// THEMES
//---------------------------------------------------------------------

/*
 * The 'live_path' is where assets will be built to and where the
 * assets should be linked to. Depending on your setup, they will either
 * be served up as static files for speed, or dynamically generated
 * for flexibility during development.
 */
$config['assets.live_path']     = FCPATH .'assets/';

/*
 * An array of other paths to look for assets in. Should be relative
 * to the FCPATH.
 */
$config['assets.other_paths']   = [];

//--------------------------------------------------------------------
// Migrations
//--------------------------------------------------------------------
$config['migrate.auto_core']	= FALSE;
$config['migrate.auto_app']		= FALSE;