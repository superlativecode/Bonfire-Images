Bonfire Images
===============

A simple images module. This is not a standalone module and should be used with [Bonfire-Blog](https://github.com/superlativecode/Bonfire-Blog) or intergrate it into your own bonfire modules.

###Dependices not included

*   [Bonfire](https://github.com/ci-bonfire/Bonfire) by [Superlative Code](http://superlativecode.com/)

###Installation

**Note:** We assume you have a working instance of Bonfire running.

1.  `cd ./path/to/modules/`
2.  `git clone https://github.com/superlativecode/Bonfire-Images ./images`
3.  Login to the admin panel and migrate to the latest version of Images
4.  Move `/application/modules/images/public/assets/images/spritemap*.png` to `/public/assets/images`
5.  Copy the libraries in `/application/modules/images/libraries` to `/application/libaries`
6.  Create folder `/public/uploads/` with permissions for read and write
7.  Make sure each module has the correct permissions to access it
8.  Add ./public/uploads to your .gitignore

###Features

*   Drag and drop images
*   Main Image
*   Image Title
*   Automatic Square thumbnails

###Libraries Used

*   [Parsedown](http://parsedown.org/)
*   [Dropzone.js](http://www.dropzonejs.com/)

###TODO

*   Fix dropzone bug where the progress bar never stops
*   Make upload path more configurable
*   Drag and drop ordering


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/superlativecode/bonfire-images/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

