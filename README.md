# Bonfire Next

This repo holds a new way of thinking about the [Bonfire Project](http://cibonfire.com). This version brings Bonfire and CodeIgniter (as much as it can) into the current methodologies of development, and utilizes the power of [Composer](http://getcomposer.org) to make including just exactly the parts you need for your current project as simple as possible. 

## Why A New Version
Bonfire had become a beast and, on many of the new projects I worked on, it was simply too much. Too much overhead, too many features, etc. At the same time I had been working more and more with Laravel and trying to figure out how Bonfire could best be a part of the larger PHP and CodeIgniter communities, instead of simply holed off in it's own little corner. 

## The New Thinking
In this new version (under heavy development) I'm going to take the following approaches whenever possible: 

* **Keep CodeIgniter as bare as possible**. In other words, don't require any more magic than necessary to make the various parts fit together. Make it so that someone with CodeIgniter experience can jump in as simply as possible, and just get rolling. 
* **Only use what's needed**. Not every project needs all of the bells and whistles that Bonfire can provide. Separate the parts out into smaller modules that can be included when needed. Provide the minimal glue necessary to make it all work together in a cohesive manner.
* **Make Use of the Community** We need to stop rewriting the different parts of the app and make use of the wider PHP communities packages where appropriate. This means things like Asset management, etc, will rely on external packages, like Assetic instead of the previous versions we were using. 
* **Be Part of the Community** Where possible, make our packages work with any CodeIgniter project, or, in the rarer cases, with PHP in general.

