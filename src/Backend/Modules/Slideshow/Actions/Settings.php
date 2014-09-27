<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;

/**
 * This is the settings-action, it will display a form to set general slideshow settings
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Settings extends BackendBaseActionEdit
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();
        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    /**
     * Loads the settings form
     */
    private function loadForm()
    {
        // add form
        $this->frm = new BackendForm('settings');

        // add form elements
        $this->frm->addCheckbox(
            'settings_per_slide', (BackendModel::getModuleSetting($this->URL->getModule(), 'settings_per_slide', false) === 'true' ? true : false)
        );
        $this->frm->addDropdown(
            'animation_type',
            array(
                'slide' => BL::lbl('SlideshowSlide', $this->getModule()),
                'fade' => BL::lbl('SlideshowFade', $this->getModule())
            ),
            BackendModel::getModuleSetting(
                $this->URL->getModule(),
                'animation_type',
                'slide'
            )
        );
        $this->frm->addDropdown(
            'slide_direction',
            array(
                'horizontal' => BL::lbl('SlideshowHorizontal', $this->getModule()),
                'vertical' => BL::lbl('SlideshowVertical', $this->getModule())),
            BackendModel::getModuleSetting(
                $this->URL->getModule(),
                'slide_direction',
                'horizontal'
            )
        );
        $this->frm->addDropdown(
            'slideshow_speed',
            array_combine(range(1, 30), range(1, 30)),
            BackendModel::getModuleSetting(
                $this->URL->getModule(),
                'slideshow_speed',
                7)
        );
        $this->frm->addDropdown(
            'animation_duration',
            array_combine(range(1, 5), range(1, 5)),
            BackendModel::getModuleSetting(
                $this->URL->getModule(),
                'animation_duration',
                1
            )
        );
        $this->frm->addCheckbox('direction_navigation', (BackendModel::getModuleSetting($this->URL->getModule(), 'direction_navigation', false) === 'true' ? true : false));
        $this->frm->addCheckbox('control_navigation', (BackendModel::getModuleSetting($this->URL->getModule(), 'control_navigation', false) === 'true' ? true : false));
        $this->frm->addCheckbox('thumbnail_navigation', (BackendModel::getModuleSetting($this->URL->getModule(), 'thumbnail_navigation', false) === 'true' ? true : false));
        $this->frm->addCheckbox('keyboard', (BackendModel::getModuleSetting($this->URL->getModule(), 'keyboard', false) === 'true' ? true : false));
        $this->frm->addCheckbox('mousewheel', (BackendModel::getModuleSetting($this->URL->getModule(), 'mousewheel', false) === 'true' ? true : false));
        $this->frm->addCheckbox('touch', (BackendModel::getModuleSetting($this->URL->getModule(), 'touch', false) === 'true' ? true : false));
        $this->frm->addCheckbox('random_order', (BackendModel::getModuleSetting($this->URL->getModule(), 'random_order', false) === 'true' ? true : false));
        $this->frm->addCheckbox('auto_animate', (BackendModel::getModuleSetting($this->URL->getModule(), 'auto_animate', false) === 'true' ? true : false));
        $this->frm->addCheckbox('animation_loop', (BackendModel::getModuleSetting($this->URL->getModule(), 'animation_loop', false)=== 'true' ? true : false));
    }

    /**
     * Validates the settings form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();

            if ($this->frm->isCorrect()) {
                // set mode setting
                BackendModel::setModuleSetting($this->URL->getModule(), 'settings_per_slide', (string) ($this->frm->getField('settings_per_slide')->getChecked()) ? 'true' : '');

                // set main settings
                BackendModel::setModuleSetting($this->URL->getModule(), 'animation', (string) $this->frm->getField('animation_type')->getValue());
                BackendModel::setModuleSetting($this->URL->getModule(), 'direction', (string) $this->frm->getField('slide_direction')->getValue());
                BackendModel::setModuleSetting($this->URL->getModule(), 'slideshow_speed', (int) $this->frm->getField('slideshow_speed')->getValue());
                BackendModel::setModuleSetting($this->URL->getModule(), 'animation_speed', (int) $this->frm->getField('animation_duration')->getValue());

                // set optional settings
                BackendModel::setModuleSetting($this->URL->getModule(), 'direction_navigation', (string) ($this->frm->getField('direction_navigation')->getChecked()) ? 'true' : 'false');
                BackendModel::setModuleSetting($this->URL->getModule(), 'control_navigation', (string) ($this->frm->getField('control_navigation')->getChecked()) ? 'true' : 'false');
                BackendModel::setModuleSetting($this->URL->getModule(), 'thumbnail_navigation', (string) ($this->frm->getField('thumbnail_navigation')->getChecked()) ? 'true' : '');
                BackendModel::setModuleSetting($this->URL->getModule(), 'keyboard', (string) ($this->frm->getField('keyboard')->getChecked()) ? 'true' : 'false');
                BackendModel::setModuleSetting($this->URL->getModule(), 'mousewheel', (string) ($this->frm->getField('mousewheel')->getChecked()) ? 'true' : 'false');
                BackendModel::setModuleSetting($this->URL->getModule(), 'touch', (string) ($this->frm->getField('touch')->getChecked()) ? 'true' : 'false');
                BackendModel::setModuleSetting($this->URL->getModule(), 'randomize', (string) ($this->frm->getField('random_order')->getChecked()) ? 'true' : 'false');
                BackendModel::setModuleSetting($this->URL->getModule(), 'auto_animate', (string) ($this->frm->getField('auto_animate')->getChecked()) ? 'true' : 'false');
                BackendModel::setModuleSetting($this->URL->getModule(), 'animation_loop', (string) ($this->frm->getField('animation_loop')->getChecked()) ? 'true' : 'false');

                // trigger event
                BackendModel::triggerEvent($this->getModule(), 'after_saved_settings');

                // redirect to the settings page
                $this->redirect(BackendModel::createURLForAction('Settings') . '&report=saved');
            }
        }
    }
}
