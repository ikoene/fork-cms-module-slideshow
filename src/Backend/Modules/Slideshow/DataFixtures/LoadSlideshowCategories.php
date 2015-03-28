<?php

namespace Backend\Modules\Slideshow\DataFixtures;

class LoadSlideshowCategories
{

    private $db;

    public function load(\SpoonDatabase $db)
    {
        $this->db = $db;

        // load install.sql
        $this->loadSlideshowModule();

        $metaId = $this->db->insert(
            'meta',
            array(
                'keywords' => 'SlideshowCategory for tests',
                'description' => 'SlideshowCategory for tests',
                'title' => 'SlideshowCategory for tests',
                'url' => 'slideshowcategory-for-tests',
            )
        );

        $this->db->insert(
            'slideshow_categories',
            array(
                'meta_id' => $metaId,
                'language' => 'en',
                'title' => 'SlideshowCategory for tests',
            )
        );
    }

    public function loadSlideshowModule()
    {
        // import SQL
        $this->importSQL(dirname(__FILE__) . '/../Installer/Data/install.sql');
    }

    /**
     * Imports the sql file
     *
     * @param string $filename The full path for the SQL-file.
     */
    protected function importSQL($filename)
    {
        // load the file content and execute it
        $content = trim(file_get_contents($filename));

        // file actually has content
        if (!empty($content)) {
            /**
             * Some versions of PHP can't handle multiple statements at once, so split them
             * We know this isn't the best solution, but we couldn't find a beter way.
             */
            $queries = preg_split("/;(\r)?\n/", $content);

            // loop queries and execute them
            foreach ($queries as $query) {
                $this->db->execute($query);
            }
        }
    }

    /**
     * Inserts a new module.
     * The getModule method becomes available after using addModule and returns $module parameter.
     *
     * @param string $module The name of the module.
     */
    protected function addModule($module)
    {
        $this->module = (string) $module;

        // module does not yet exists
        if (!(bool) $this->getDB()->getVar('SELECT 1 FROM modules WHERE name = ? LIMIT 1', $this->module)) {
            // build item
            $item = array(
                'name' => $this->module,
                'installed_on' => gmdate('Y-m-d H:i:s')
            );

            // insert module
            $this->getDB()->insert('modules', $item);
        } else {
            // activate and update description
            $this->getDB()->update('modules', array('installed_on' => gmdate('Y-m-d H:i:s')), 'name = ?', $this->module);
        }
    }
}
