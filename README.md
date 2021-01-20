# Fix incorrectly marked up directories in the Wordpress Media Library 

This is a drop-in template to fix incorrect directories being listed for images when from migrating away from [Media Cloud
](https://github.com/Interfacelab/ilab-media-tools).

When deactivating the plugin I have found a handful of images will have incorrect directories assigned to them. 

For example; an image uploaded in Jan 2020, will be have /2015/01 instead of /2020/01. 

I don't know why this is. 

I just know it's a PITA when dealing with hundreds of images.


## Getting Started

This template simply runs through all your images; checks if the master file exists in the directory it thinks it should; it doesn't it will then match the filename against all the files found in your uploads directory. When it finds a match this will update the media items metadata with the correct path.  

Note. If you have an identically named file in a different month it might incorrectly match with this. We only have the filename to go off when tracking down the real file.

### Prerequisites

PHP 7.0.

### Installing

* Backup your database before running.
* Copy imagefix.php your active themes directory.
* Create a page and assign it as a template. 
* Open the page. The template will output text to tell you what it is doing. 

## Deployment

Add additional notes about how to deploy this on a live system

## Acknowledgments

* Hat tip to anyone whose code was used