<?php

namespace Backend\Modules\Slideshow\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the slideshow module
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Installer extends ModuleInstaller
{
    /**
     * Install the module
     *
     * @return  void
     */
    public function install()
    {
        // load install.sql
        $this->importSQL(dirname(__FILE__) . '/Data/install.sql');

        // add 'slideshow' as a module
        $this->addModule('Slideshow');

        // import locale
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        // module rights
        $this->setModuleRights(1, 'Slideshow');

        // action rights
        $this->setActionRights(1, 'Slideshow', 'Index');
        $this->setActionRights(1, 'Slideshow', 'Add');
        $this->setActionRights(1, 'Slideshow', 'Edit');
        $this->setActionRights(1, 'Slideshow', 'Delete');
        $this->setActionRights(1, 'Slideshow', 'Sequence');
        $this->setActionRights(1, 'Slideshow', 'Categories');
        $this->setActionRights(1, 'Slideshow', 'AddCategory');
        $this->setActionRights(1, 'Slideshow', 'EditCategory');
        $this->setActionRights(1, 'Slideshow', 'DeleteCategory');
        $this->setActionRights(1, 'Slideshow', 'AddImage');
        $this->setActionRights(1, 'Slideshow', 'EditImage');
        $this->setActionRights(1, 'Slideshow', 'DeleteImage');

        // Slideshow settings
        $this->setSetting('Slideshow', 'animation_type', 'slide');
        $this->setSetting('Slideshow', 'slide_direction', 'horizontal');
        $this->setSetting('Slideshow', 'slideshow_speed', 7);
        $this->setSetting('Slideshow', 'animation_duration', 1);
        $this->setSetting('Slideshow', 'direct_navigation', 'true');
        $this->setSetting('Slideshow', 'control_navigation', 'true');
        $this->setSetting('Slideshow', 'keyboard_navigation', 'true');
        $this->setSetting('Slideshow', 'mousewheel_navigation', 'false');
        $this->setSetting('Slideshow', 'random_order', 'false');
        $this->setSetting('Slideshow', 'auto_animate', 'true');
        $this->setSetting('Slideshow', 'animation_loop', 'true');
        $this->setSetting('Slideshow', 'settings_per_slide', 'true');

        // connect to database
        $db = $this->getDB();

        // insert default category for every language
        foreach ($this->getLanguages() as $language) {
            $metaId = $db->insert(
                'meta',
                array(
                    'keywords' => 'default',
                    'description' => 'default',
                    'title' => 'default',
                    'url' => 'default'
                )
            );
            $db->insert(
                'slideshow_categories',
                array(
                    'meta_id' => $metaId,
                    'language'=> $language,
                    'title' => 'default',
                    'sequence' => 1
                )
            );
        }

        // module extra
        $extraBlockId = $this->insertExtra('Slideshow', 'block', 'Slideshow', null, null);

        // settings navigation
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, 'Slideshow', 'slideshow/settings');

        // set navigation
        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationBlogId = $this->setNavigation($navigationModulesId, 'Slideshow');
        $this->setNavigation(
            $navigationBlogId,
            'Galleries',
            'slideshow/index',
            array(
                'slideshow/add',
                'slideshow/edit',
                'slideshow/edit_image',
                'slideshow/add_image'
            )
        );
        $this->setNavigation(
            $navigationBlogId,
            'Categories',
            'slideshow/categories',
            array(
                'slideshow/add_category',
                'slideshow/edit_category'
            )
        );
    }
}
