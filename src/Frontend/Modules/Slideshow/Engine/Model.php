<?php

namespace Frontend\Modules\Slideshow\Engine;

use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;

/**
 * In this file we store all generic functions that we will be using in the slideshow module
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Model
{
    /**
     * Get all galleries
     *
     * @return array
     */
    public static function getGalleries()
    {
        return (array) FrontendModel::getContainer()->get('database')->getRecords(
            'SELECT DISTINCT i.*, m.url, m2.url as category_url, c.title as category_title
            FROM slideshow_galleries AS i
            INNER JOIN slideshow_images as p ON i.id = p.gallery_id
            INNER JOIN slideshow_categories AS c ON i.category_id = c.id
            INNER JOIN meta as m ON i.meta_id = m.id
            INNER JOIN meta AS m2 ON c.meta_id = m2.id
            WHERE i.language = ? AND i.hidden = ? AND i.publish_on <= ? AND p.hidden = ?
            ORDER BY i.publish_on',
            array(FRONTEND_LANGUAGE, 'N', FrontendModel::getUTCDate('Y-m-d H:i') . ':00','N')
        );
    }

    /**
     * Get the gallery based on the URL
     *
     * @param  array $URL The URL.
     * @return array
     */
    public static function getGalleryByURL($URL)
    {
        $db = FrontendModel::getContainer()->get('database');

        return (array) $db->getRecord(
            'SELECT
            i.*,
            m.*,
            i.id AS gallery_id,
            UNIX_TIMESTAMP(i.publish_on) AS publish_on,
            m.id AS meta_id,
            m.keywords AS meta_keywords,
            m.title AS meta_title,
            m.description AS meta_description
            FROM slideshow_galleries AS i
            INNER JOIN meta AS m ON i.meta_id = m.id
            WHERE m.url = ? AND i.language = ? AND i.hidden = ?
            GROUP BY i.id',
            array((string) $URL, FRONTEND_LANGUAGE, 'N')
        );
    }

    /**
     * Get the galleries in a category based on the URL (+ get the meta of the category itself for overwrite)
     *
     * @param  array $URL The URL.
     * @return array
     */
    public static function getGalleriesByURL($URL)
    {
        $db = FrontendModel::getContainer()->get('database');

        return (array) $db->getRecords(
            'SELECT DISTINCT i.*, m.url as category_url,
            m2.url as category_meta_url, m2.keywords as category_meta_keywords,
            m2.title as category_meta_title, m2.description as category_meta_description,
            m2.title_overwrite as category_title_overwrite,
            m2.description_overwrite as category_description_overwrite,
            m2.keywords_overwrite as category_keywords_overwrite,
            m.url as meta_url, m.keywords AS meta_keywords,
            m.id AS meta_id, m.title AS meta_title, m.description AS meta_description,
            c.title as category_title
            FROM slideshow_galleries AS i
            INNER JOIN slideshow_images as p ON i.id = p.gallery_id
            INNER JOIN slideshow_categories AS c ON i.category_id = c.id
            INNER JOIN meta as m ON i.meta_id = m.id
            INNER JOIN meta AS m2 ON c.meta_id = m2.id
            WHERE m2.url = ? AND i.language = ? AND i.hidden = ?
            AND i.publish_on <= ? AND p.hidden = ?
            ORDER BY i.sequence',
            array($URL, FRONTEND_LANGUAGE, 'N', FrontendModel::getUTCDate('Y-m-d H:i') . ':00','N')
        );
    }

    /**
     * Get all images in a gallery
     *
     * @return array
     * @param  int   $categoryId The id of the gallery.
     */
    public static function getImages($id)
    {
        $records = (array) FrontendModel::getContainer()->get('database')->getRecords(
            'SELECT i.*
            FROM slideshow_images AS i
            WHERE i.gallery_id = ? AND i.hidden = ? AND i.language = ?
            ORDER BY i.sequence',
            array((int) $id, 'N', FRONTEND_LANGUAGE)
        );

        foreach($records as $key => $record)
        {

            $records[$key]['data'] = unserialize($record['data']);

            // is there a link given?
            if($records[$key]['data']['link'] !== null)
            {
                // set the external option. This allows us to link to external sources
                $external = ($records[$key]['data']['link']['type'] == 'external');
                $records[$key]['data']['link']['external'] = $external;

                // if this is an internal page, we need to build the url since we have the id
                if(!$external)
                {
                    $extraId = $records[$key]['data']['link']['id'];
                    $records[$key]['data']['link']['url'] = FrontendNavigation::getURL($extraId);
                }
            }
        }

        return $records;
    }

    /**
     * Get a gallery by id
     *
     * @return array
     * @param  int   $galleryId The id of the gallery.
     */
    public static function getGallery($id)
    {
        return (array) FrontendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*, UNIX_TIMESTAMP(i.publish_on) AS publish_on
            FROM slideshow_galleries AS i
            INNER JOIN slideshow_images as p ON i.id = p.gallery_id
            WHERE i.id = ? AND i.language = ? AND i.hidden = ?
            AND i.publish_on <= ? AND p.hidden = ?
            ORDER BY i.sequence',
            array(
                (int) $id,
                FRONTEND_LANGUAGE,
                'N',
                FrontendModel::getUTCDate('Y-m-d H:i') . ':00',
                'N'
            )
        );
    }

    /**
     * Get all settings for a specific slideshow
     *
     * @param  int $id The slideshow id
     * @param  array $keys
     * @return array
     */
    public static function getAllSettings($id)
    {
        $results = array();

        $settings = (array) FrontendModel::getContainer()->get('database')->getRecords(
            'SELECT ss.key, ss.value
             FROM slideshow_settings AS ss
             WHERE ss.slideshow_id = ?',
            array((int) $id)
        );

        // unserialize settings
        foreach($settings as $setting)
        {
            $results[$setting['key']] = unserialize($setting['value']);
        }

        return $results;
    }

    /**
     * Get an array with the previous and the next gallery
     *
     * @param  int   $id The id of the current item.
     * @return array
     */
    public static function getNavigation($id)
    {
        // redefine
        $id = (int) $id;

        // get db
        $db = FrontendModel::getContainer()->get('database');

        // get date for current item
        $date = (string) $db->getVar(
            'SELECT i.publish_on
             FROM slideshow_galleries AS i
             WHERE i.id = ?',
            array($id)
        );

        // validate
        if ($date == '') {
            return array();
        }

        // init var
        $navigation = array();

        // get previous post
        $navigation['previous'] = $db->getRecord(
            'SELECT i.id, i.title, m.url
             FROM slideshow_galleries AS i
             INNER JOIN meta AS m ON i.meta_id = m.id
             INNER JOIN slideshow_images as p ON p.gallery_id = i.id
             WHERE i.id != ? AND i.hidden = ? AND i.language = ? AND i.publish_on <= ? AND p.hidden = ?
             ORDER BY i.publish_on DESC
             LIMIT 1',
            array($id, 'N', FRONTEND_LANGUAGE, $date, 'N')
        );

        // get next post
        $navigation['next'] = $db->getRecord(
            'SELECT i.id, i.title, m.url
             FROM slideshow_galleries AS i
             INNER JOIN meta AS m ON i.meta_id = m.id
             INNER JOIN slideshow_images as p ON p.gallery_id = i.id
             WHERE i.id != ? AND i.hidden = ? AND i.language = ? AND i.publish_on > ? AND p.hidden = ?
             ORDER BY i.publish_on ASC
             LIMIT 1',
            array($id, 'N', FRONTEND_LANGUAGE, $date, 'N')
        );
        return $navigation;
    }
}
