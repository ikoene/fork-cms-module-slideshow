# Module: Slideshow

Slideshow is a module for Fork CMS. This module manages responsive slideshows. It makes use of Flexslider, a responsive jQuery slider plugin. Enjoy!

## Installation

Visit the [Fork CMS knowledge base](http://fork-cms.com/knowledge-base) to learn how to install a module.

## Updates

** Version 2.0.0 **

*This is a major release, meaning you shouldn't just upgrade by replacing the files. A complete reinstall is required.*

- Update to Flexslider v2.2.2
- Add thumbnail navigation
- Add internal/external slide linking
- Serialize slideshow settings
- Add author to a slideshow
- Slideshow settings are parsed to a separate javascript file
- Better overall form validation
- Add view button
- Add DeleteImage action
- Add direct image link on dataGrid overview
- Refactor several methods in the model
- Bugfix - dynamic link building based on title
- Bugfix - delete all images from filesystem when deleting a gallery

## Features

<ul>
	<li>Add the module to a page, or use dynamic widgets</li>
	<li>Sort galleries with drag&drop</li>
	<li>(optional) SEO settings for every gallery</li>
	<li>All galleries have a publishing time and date</li>
	<li>Upload multiple images at once</li>
	<li>Publish, delete or hide individual images</li>
	<li>Set several options for slideshows like animation type, animation time, slideshow speed and navigation type. While you can set these settings for <b>all</b> slideshows (via module settings), you can also define <b>individual</b> settings for every slideshow separately. </li>
</ul>

## Notes

When creating a gallery, you'll be asked to give a width and height. You can fill in both or only the width. If you choose the latter, images will scale proportionally based upon the width. Give in both width and height, and images will be cropped to the desired dimensions.

## Tests

With the introduction of tests in Fork CMS, some functional tests are now added to the module. More info [here](http://www.fork-cms.com/blog/detail/forkathon-introducing-tests).

I've yet to find a way to easily add the Slideshow module tables to the test database. For now, I'm using a copy of the database on which I've installed the module. Follow these steps:

* Install Fork CMS (english required)
* Install the Slideshow module
* Add the module to a new page and name it 'Slideshow'
* Take an export of the database and place it in /tools/copy_db.sql
* Edit /src/Common/WebTestCase.php and use copy_db.sql instead of test_db.sql

        $client->getContainer()->get('database'),
          file_get_contents($kernelDir . '/../tools/copy_db.sql')
        );
* Use this command to run the tests

        bin/phpunit -c app --filter=Slideshow
* Profit!


## Support

<ul>
	<li>Twitter: @ikoene</li>
	<li>E-mail: koen@tagz.be</li>
</ul>
