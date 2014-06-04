<?php

//--------------------------------------------------------------------
// ACTIVITIES
//--------------------------------------------------------------------

/*
	If TRUE, will log activities to the database using the activity_model's
	log_activity. If this is FALSE, you can remove the Activity module
	without repercussions.
 */
$config['enable_activity_logging'] = TRUE;

//--------------------------------------------------------------------
// !Migrations
//--------------------------------------------------------------------
$config['migrate.auto_core']	= FALSE;
$config['migrate.auto_app']		= FALSE;