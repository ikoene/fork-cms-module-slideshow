<?php

namespace Backend\Modules\Slideshow\Engine;

use Common\Uri as CommonUri;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;

/**
 * In this file we store all generic functions that we will be using in the slideshow module
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Model
{
    /**
     * Query to retrieve all galleries
     *
     * @var string
     */
    const QRY_DATAGRID_BROWSE =
        'SELECT i.id, i.category_id, i.filename, i.title, i.width, i.height, i.hidden, i.sequence, i.publish_on
        FROM slideshow_galleries AS i
        WHERE i.language = ? AND i.category_id = ?
        ORDER BY i.sequence ASC';

    /**
     * Query to retrieve all images
     *
     * @var string
     */
    const QRY_DATAGRID_BROWSE_IMAGES =
        'SELECT i.id, i.title,i.description, i.filename, i.hidden as image_hidden, i.sequence
        FROM slideshow_images AS i
        WHERE i.gallery_id = ?
        ORDER BY i.sequence ASC';

    /**
     * Query to retrieve all categories
     *
     * @var string
     */
    const QRY_DATAGRID_BROWSE_CATEGORIES =
        'SELECT i.id, i.title, i.sequence
        FROM slideshow_categories AS i
        WHERE i.language = ?
        ORDER BY i.sequence ASC';

    /**
     * Query to retrieve all widgets
     *
     * @var string
     */
    const QRY_DATAGRID_BROWSE_WIDGETS =
        'SELECT i.id, i.title
        FROM slideshow_widgets AS i
        WHERE i.language = ?';

    /**
     * Generate html for preview
     *
     * @return  array
     */
    public static function getPreview($filename)
    {
        $path = '/src/Frontend/Files/userfiles/images/slideshow/thumbnails/' . $filename;
        return '<img src="' . $path . '" width="50" height="50" />';
    }

    /**
     * Generate html for gallerypreview
     *
     * @return array
     */
    public static function getGalleryPreview($filename)
    {
        // the url allready exists
        if ($filename != null) {
            $path = '/src/Frontend/Files/userfiles/images/slideshow/thumbnails/' . $filename;
            return '<img src="' . $path . '" width="50" height="50" />';
        }
    }

    /**
     * Delete a specific category
     *
     * @return  void
     * @param   int $id The id of the category to be deleted.
     */
    public static function deleteCategory($id)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // get item
        $item = self::getCategory($id);

        // build extra
        $extra = array('id' => $item['extra_id'],
                        'module' => 'Slideshow',
                        'type' => 'block',
                        'action' => 'Category');

        // delete extra
        $db->delete(
            'modules_extras',
            'id = ? AND module = ? AND type = ? AND action = ?',
            array($extra['id'], $extra['module'], $extra['type'], $extra['action'])
        );

        // delete the record
        $db->delete('slideshow_categories', 'id = ?', array((int) $id));
    }

    /**
     * Create a widget
     *
     * @return  void
     * @param   int $item The details of the category to be deleted.
     */
    public static function insertWidgetExtras($item)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // build extra
        $extra = array( 'id' => null,
                        'module' => 'Slideshow',
                        'type' => 'widget',
                        'label' => 'Slideshow',
                        'action' => 'Slideshow',
                        'data' =>serialize(
                            array(
                                'extra_label' => $item['title'],
                                'gallery_id' => $item['gallery_id'],
                                'language' => BL::getWorkingLanguage()
                            )
                        ),
                        'hidden' =>'N',
                        'sequence' =>10000);


        // insert and return the new id
        $item['id'] = $db->insert('modules_extras', $extra);

        // return the new id
        return $item['id'];
    }

    /**
     * Retrieve the unique URL for a gallery
     *
     * @param string $url
     * @param int[optional] $id The id of the category to ignore.
     * @return string
     */
    public static function getURLForGallery($URL, $id = null)
    {
        // redefine URL
        $URL = CommonUri::getUrl((string) $URL);

        // get db
        $db = BackendModel::getContainer()->get('database');

        // a new gallery is added
        if ($id === null) {
            // check if there's allready a gallery with the suggested url
            $number = (int) $db->getVar(
                'SELECT COUNT(i.id)
                FROM slideshow_galleries AS i
                INNER JOIN meta AS m ON i.meta_id = m.id
                WHERE i.language = ? AND m.url = ?',
                array(BL::getWorkingLanguage(), $URL)
            );

            // the url allready exists
            if ($number != 0) {
                // add a number to the url
                $URL = BackendModel::addNumber($URL);

                // retry with the new url
                return self::getURLForGallery($URL);
            }

        // a gallery is edited
        } else {
            // check if there's allready a gallery with the suggested url, expect the gallery itself
            $number = (int) $db->getVar(
                'SELECT COUNT(i.id)
                FROM slideshow_galleries AS i
                INNER JOIN meta AS m ON i.meta_id = m.id
                WHERE i.language = ? AND i.id <> ? AND m.url = ?',
                array(BL::getWorkingLanguage(), $id, $URL)
            );

            // the url allready exists
            if ($number != 0) {
                // add a number to the url
                $URL = BackendModel::addNumber($URL);

                // retry with the new url
                return self::getURLForGallery($URL, $id);
            }
        }

        // one does not simply return an URL
        return $URL;
    }

    /**
     * Retrieve the unique URL for a category
     *
     * @param string $url
     * @param int[optional] $id The id of the category to ignore.
     * @return string
     */
    public static function getURLForCategory($url, $id = null)
    {
        $url = CommonUri::getUrl((string) $url);
        $db = BackendModel::getContainer()->get('database');

        // new category
        if ($id === null) {
            if ((bool) $db->getVar(
                'SELECT 1
                 FROM slideshow_categories AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url)
            )
        ) {
                $url = BackendModel::addNumber($url);

                return self::getURLForCategory($url);
            }
        } else {
            // current category should be excluded
            if ((bool) $db->getVar(
                'SELECT 1
                 FROM slideshow_categories AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ? AND i.id != ?
                 LIMIT 1',
                array(BL::getWorkingLanguage(), $url, $id)
            )
        ) {
                $url = BackendModel::addNumber($url);

                return self::getURLForCategory($url, $id);
            }
        }

        return $url;
    }

    /**
     * Update a widget
     *
     * @return  void
     * @param   int $item The details of the category to be deleted.
     */
    public static function updateWidgetExtras($item)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // build extra
        $extra = array('id' => $item['extra_id'],
                        'module' => 'Slideshow',
                        'type' => 'widget',
                        'label' => 'Slideshow',
                        'action' => 'Slideshow',
                        'data' =>serialize(
                            array(
                                'extra_label' => $item['title'],
                                'gallery_id' => $item['id'],
                                'language' => BL::getWorkingLanguage()
                            )
                        ),
                        'hidden' =>'N',
                        'sequence' =>10000);

        // update extra
        return $item['id'] = $db->update(
            'modules_extras',
            $extra,
            'id = ? AND module = ? AND type = ? AND action = ?',
            array(
                $extra['id'],
                $extra['module'],
                $extra['type'],
                $extra['action']
            )
        );

        // return the new id
        return $item['id'];

    }

    /**
     * Delete a specific gallery
     *
     * @return  void
     * @param   int $id The id of the gallery to be deleted.
     */
    public static function deleteGallery($id)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // get meta id
        $metaId = (int) $db->getVar(
            'SELECT i.meta_id
             FROM slideshow_galleries AS i
             WHERE i.id = ?',
            array((int) $id)
        );

        // delete meta
        if (!empty($metaId)) {
            $db->delete('meta', 'id = ?', (int) $metaId);
        }

        // delete the record
        $db->delete('slideshow_galleries', 'id = ?', array((int) $id));

        //delete images in slideshow_images
        $db->delete('slideshow_images', 'gallery_id = ?', array((int) $id));
    }

    /**
     * Delete the settings of a gallery
     *
     * @return  void
     * @param   int $id The id of the gallery to be deleted.
     */
    public static function deleteGallerySettings($id)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // delete the record
        $db->delete('slideshow_settings', 'gallery_id = ?', array((int) $id));
    }

    /**
     * Delete a specific image
     *
     * @return  void
     * @param   int $id The id of the image to be deleted.
     */
    public static function deleteImage($id)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // delete the record
        $db->delete('slideshow_images', 'id = ?', array((int) $id));
    }

    /**
     * Delete a widget
     *
     * @return  void
     * @param   int $id The id of the widget to be deleted.
     */
    public static function deleteWidget($id)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // get item
        $item = self::getGallery($id);

        // build extra
        $extra = array( 'module' => 'Slideshow',
                        'type' => 'widget',
                        'id' => $item['extra_id']
                        );

        // delete extra
        $db->delete(
            'modules_extras',
            'module = ? AND type = ? AND id = ?',
            array(
                $extra['module'],
                $extra['type'],
                $extra['id']
            )
        );
    }

    /**
     * Checks if a category exists
     *
     * @return  bool
     * @param   int $id The id of the category to check for existence.
     */
    public static function existsCategory($id)
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
            FROM slideshow_categories AS i
            WHERE i.id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Checks if a widget exists
     *
     * @return  bool
     * @param   int $id The id of the widget to check for existence.
     */
    public static function existsWidget($id)
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
            FROM slideshow_widgets AS i
            WHERE i.id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Checks if a gallery exists
     *
     * @return  bool
     * @param   int $id The id of the gallery to check for existence.
     */
    public static function existsGallery($id)
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
            FROM slideshow_galleries AS i
            WHERE i.id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Get the number of images per gallery
     *
     * @return  bool
     * @param   int $id The id of the category to check for existence.
     */
    public static function getImagesByGallery($id)
    {
        return (int) BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
            FROM slideshow_images AS i
            WHERE i.gallery_id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Get the number of galleries per category
     *
     * @return  bool
     * @param   int $id The id of the category to check for existence.
     */
    public static function getGalleriesByCategory($id)
    {
        return (int) BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
            FROM slideshow_galleries AS i
            WHERE i.category_id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Checks if an image exists
     *
     * @return  bool
     * @param   int $id The id of the image to check for existence.
     */
    public static function existsImage($id)
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
            FROM slideshow_images AS i
            WHERE i.id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Get all categories
     *
     * @return  array
     */
    public static function getCategories()
    {
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT i.*
            FROM slideshow_categories AS i
            WHERE i.language = ?
            ORDER BY i.sequence ASC',
            array(BL::getWorkingLanguage())
        );
    }

    /**
     * Get all active categories
     *
     * @return  array
    */
    public static function getActiveCategories($includeCount = false)
    {
        $db = BackendModel::getContainer()->get('database');

        if ($includeCount) {
            return (array) $db->getPairs(
                'SELECT DISTINCT i.id, CONCAT(i.title, " (",  COUNT(p.category_id) ,")") AS title
                FROM slideshow_categories AS i
                INNER JOIN slideshow_galleries AS p ON i.id = p.category_id AND i.language = p.language
                WHERE i.language = ?
                GROUP BY i.id
                ORDER BY i.sequence',
                array(BL::getWorkingLanguage())
            );
        }

        return (array) $db->getPairs(
            'SELECT i.id as caste i.title
            FROM slideshow_categories AS i
            WHERE i.language = ?',
            array(BL::getWorkingLanguage()
            )
        );
    }

    /**
     * Get all images
     *
     * @return  array
     */
    public static function getImages()
    {
        return (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT i.*
            FROM slideshow_galleries AS i
            WHERE i.language = ?
            ORDER BY i.sequence ASC',
            array(BL::getWorkingLanguage())
        );
    }

    /**
     * Get all category names for dropdown
     *
     * @return  array
     */
    public static function getCategoriesForDropdown()
    {
        return (array) BackendModel::getContainer()->get('database')->getPairs(
            'SELECT i.id, i.title
            FROM slideshow_categories AS i
            WHERE i.language = ?
            ORDER BY i.sequence ASC',
            array(BL::getWorkingLanguage())
        );
    }

    /**
     * Get all image names for dropdown
     *
     * @return  array
     */
    public static function getGalleriesForDropdown()
    {
        return (array) BackendModel::getContainer()->get('database')->getPairs(
            'SELECT i.id, i.title
            FROM slideshow_galleries AS i
            WHERE i.language = ?
            ORDER BY i.sequence ASC',
            array(BL::getWorkingLanguage())
        );
    }

    /**
     * Get the max sequence id for category
     *
     * @return  int
     * @param   int $id The category id.
     */
    public static function getMaximumCategorySequence()
    {
        return (int) BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
            FROM slideshow_categories AS i'
        );
    }

    /**
     * Get the max sequence id for image
     *
     * @return  int
     * @param   int $id The image id.
     */
    public static function getMaximumImageSequence($id)
    {
        return (int) BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
            FROM slideshow_images AS i
            WHERE i.gallery_id = ?',
            array((int) $id)
        );
    }

    /**
     * Get the max sequence id for gallery
     *
     * @return  int
     * @param   int $id The gallery id.
     */
    public static function getMaximumGallerySequence($id)
    {
        return (int) BackendModel::getContainer()->get('database')->getVar(
            'SELECT MAX(i.sequence)
            FROM slideshow_galleries AS i
            WHERE i.category_id = ?',
            array((int) $id)
        );
    }

    /**
     * Check if the category of a gallery was changed
     *
     * @return  int
     * @param   int $id The gallery id.
     */
    public static function getChangeCategory($id, $categoryId)
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT i.category_id
            FROM slideshow_galleries AS i
            WHERE i.id = ? AND i.category_id = ?',
            array((int) $id, (int) $categoryId)
        );
    }

    /**
     * Get a Gallery by id
     *
     * @return  array
     * @param   int $id The gallery id.
     */
    public static function getGallery($id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*, UNIX_TIMESTAMP(i.publish_on) AS publish_on, UNIX_TIMESTAMP(i.created_on) AS created_on,
            UNIX_TIMESTAMP(i.edited_on) AS edited_on
            FROM slideshow_galleries AS i
            WHERE i.id = ?',
            array((int) $id)
        );
    }

    /**
     * Get a Settings by id
     *
     * @return  array
     * @param   int $id The gallery id.
     */
    public static function getSettings($id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
            FROM slideshow_settings AS i
            WHERE i.gallery_id = ?',
            array((int) $id)
        );
    }

    /**
     * Get a Image by id
     *
     * @return  array
     * @param   int $id The image id.
     */
    public static function getImage($id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
            FROM slideshow_images AS i
            WHERE i.id = ?',
            array((int) $id)
        );
    }

    /**
     * Get category by id
     *
     * @return  array
     * @param   int $id The id of the category.
     */
    public static function getCategory($id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
            FROM slideshow_categories AS i
            WHERE i.id = ?',
            array((int) $id)
        );
    }

    /**
     * Get a Widget by id
     *
     * @return  array
     * @param   int $id The widget id.
     */
    public static function getWidget($id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
            FROM slideshow_widgets AS i
            WHERE i.id = ?',
            array((int) $id)
        );
    }

    /**
     * Get the last added widget
     *
     * @return  array
     * @param   int $id The widget id.
     */
    public static function getLastWidget()
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
            FROM modules_extras AS i
            ORDER BY id DESC
            LIMIT 1'
        );
    }

    /**
     * Get the last added gallery
     *
     * @return  array
     * @param   int $id The widget id.
     */
    public static function getLastGallery()
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*
            FROM slideshow_galleries AS i
            ORDER BY id DESC
            LIMIT 1'
        );
    }

    /**
     * Add a new gallery.
     *
     * @return  int
     * @param   array $item The data to insert.
     */
    public static function insertGallery(array $item)
    {
        return BackendModel::getContainer()->get('database')->insert('slideshow_galleries', $item);
    }

    /**
     * Add default settings of a new gallery.
     *
     * @return  int
     * @param   array $item The data to insert.
     */
    public static function insertGallerySettings(array $item)
    {
        return BackendModel::getContainer()->get('database')->insert('slideshow_settings', $item);
    }

    /**
     * Add a new category.
     *
     * @return  int
     * @param   array $item The data to insert.
     */
    public static function insertCategory(array $item)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // insert and return the new id
        $item['id'] = $db->insert('slideshow_categories', $item);

        // return the new id
        return $item['id'];
    }

    /**
     * Create an image item
     *
     * @return  int
     * @param   array $item The data to insert.
     */
    public static function insertImage(array $item)
    {
        return BackendModel::getContainer()->get('database')->insert('slideshow_images', $item);
    }

    /**
     * Add a new widget.
     *
     * @return  int
     * @param   array $item The data to insert.
     */
    public static function insertWidget(array $item)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // insert and return the new id
        $item['id'] = $db->insert('slideshow_widgets', $item);

        // return the new id
        return $item['id'];
    }

    /**
     * Is this category allowed to be deleted?
     *
     * @return  bool
     * @param   int $id The category id to check.
     */
    public static function isCategoryAllowedToBeDeleted($id)
    {
        return ! (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT COUNT(i.id)
            FROM slideshow_galleries AS i
            WHERE i.category_id = ?',
            array((int) $id)
        );
    }

    /**
     * Update a category item
     *
     * @return  int
     * @param   array $item The updated values.
     */
    public static function updateCategory(array $item)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // update category
        return $db->update(
            'slideshow_categories',
            $item,
            'id = ? AND language = ?',
            array($item['id'], $item['language'])
        );
    }

    public static function updateCategorySequence(array $item)
    {
        BackendModel::getContainer()->get('database')->update(
            'slideshow_categories',
            $item,
            'id = ?',
            array($item['id'])
        );
    }

    public static function updateImageSequence(array $item)
    {
        BackendModel::getContainer()->get('database')->update(
            'slideshow_images',
            $item,
            'id = ?',
            array($item['id'])
        );
    }

    public static function updateGallerySequence(array $item)
    {
        BackendModel::getContainer()->get('database')->update(
            'slideshow_galleries',
            $item,
            'id = ?',
            array($item['id'])
        );
    }

    /**
     * Update a gallery item
     *
     * @return  int
     * @param   array $item The updated item.
     */
    public static function updateGallery(array $item)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // insert and return the extra_id
        return $db->update(
            'slideshow_galleries',
            $item,
            'id = ?',
            array((int) $item['id'])
        );
    }

    /**
     * Update a gallery item
     *
     * @return  int
     * @param   array $item The updated item.
     */
    public static function updateGallerySettings(array $item)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // insert and return the extra_id
        return $db->update(
            'slideshow_settings',
            $item,
            'gallery_id = ?',
            array((int) $item['gallery_id'])
        );
    }

    /**
     * Get a galleries extra
     *
     * @return  int
     * @param   array $extra The gallery id.
     */
    public static function getGalleryExtraId($id)
    {
        // get db
        $db = BackendModel::getContainer()->get('database');

        // get meta id
        return $db->getVar(
            'SELECT i.extra_id
             FROM slideshow_galleries AS i
             WHERE i.id = ?',
            array((int) $id)
        );
    }

    /**
     * Update an image item
     *
     * @return  int
     * @param   array $item The updated item.
     */
    public static function updateImage(array $item)
    {
        return BackendModel::getContainer()->get('database')->update(
            'slideshow_images',
            $item,
            'id = ?',
            array((int) $item['id'])
        );
    }

    /**
     * Update an image item
     *
     * @param  array $ids The ids to update
     */
    public static function updateHiddenImage($ids)
    {
        BackendModel::getContainer()->get('database')->update(
            'slideshow_images',
            array('hidden' => 'Y'),
            'id IN(' . implode(',', $ids) . ')'
        );
        return $ids;
    }

    /**
     * Update an image item
     *
     * @param  array $ids The ids to update
     */
    public static function updatePublishedImage($ids)
    {
        BackendModel::getContainer()->get('database')->update(
            'slideshow_images',
            array('hidden' => 'N'),
            'id IN(' . implode(',', $ids) . ')'
        );
        return $ids;
    }
}
