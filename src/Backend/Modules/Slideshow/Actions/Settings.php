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
            'settings_per_slide', ($this->get('fork.settings')->get($this->URL->getModule(), 'settings_per_slide', false) === 'true' ? true : false)
        );
        $this->frm->addDropdown(
            'animation_type',
            array(
                'slide' => BL::lbl('SlideshowSlide', $this->getModule()),
                'fade' => BL::lbl('SlideshowFade', $this->getModule())
            ),
            $this->get('fork.settings')->get(
                $this->URL->getModule(),
                'animation_type',
                'slide'
            )
        );
        $this->frm->addDropdown(
            'direction',
            array(
                'horizontal' => BL::lbl('SlideshowHorizontal', $this->getModule()),
                'vertical' => BL::lbl('SlideshowVertical', $this->getModule())),
            $this->get('fork.settings')->get(
                $this->URL->getModule(),
                'direction',
                'horizontal'
            )
        );
        $this->frm->addDropdown(
            'slideshow_speed',
            array_combine(range(1, 30), range(1, 30)),
            $this->get('fork.settings')->get(
                $this->URL->getModule(),
                'slideshow_speed',
                7)
        );
        $this->frm->addDropdown(
            'animation_speed',
            array_combine(range(1, 5), range(1, 5)),
            $this->get('fork.settings')->get(
                $this->URL->getModule(),
                'animation_speed',
                1
            )
        );
        $this->frm->addCheckbox('direction_navigation', ($this->get('fork.settings')->get($this->URL->getModule(), 'direction_navigation', false) === 'true' ? true : false));
        $this->frm->addCheckbox('control_navigation', ($this->get('fork.settings')->get($this->URL->getModule(), 'control_navigation', false) === 'true' ? true : false));
        $this->frm->addCheckbox('thumbnail_navigation', ($this->get('fork.settings')->get($this->URL->getModule(), 'thumbnail_navigation', false) === 'true' ? true : false));
        $this->frm->addCheckbox('keyboard', ($this->get('fork.settings')->get($this->URL->getModule(), 'keyboard', false) === 'true' ? true : false));
        $this->frm->addCheckbox('mousewheel', ($this->get('fork.settings')->get($this->URL->getModule(), 'mousewheel', false) === 'true' ? true : false));
        $this->frm->addCheckbox('touch', ($this->get('fork.settings')->get($this->URL->getModule(), 'touch', false) === 'true' ? true : false));
        $this->frm->addCheckbox('randomize', ($this->get('fork.settings')->get($this->URL->getModule(), 'randomize', false) === 'true' ? true : false));
        $this->frm->addCheckbox('auto_animate', ($this->get('fork.settings')->get($this->URL->getModule(), 'auto_animate', false) === 'true' ? true : false));
        $this->frm->addCheckbox('animation_loop', ($this->get('fork.settings')->get($this->URL->getModule(), 'animation_loop', false)=== 'true' ? true : false));
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
                $this->get('fork.settings')->set($this->URL->getModule(), 'settings_per_slide', (string) ($this->frm->getField('settings_per_slide')->getChecked()) ? 'true' : '');

                // set main settings
                $this->get('fork.settings')->set($this->URL->getModule(), 'animation_type', (string) $this->frm->getField('animation_type')->getValue());
                $this->get('fork.settings')->set($this->URL->getModule(), 'direction', (string) $this->frm->getField('direction')->getValue());
                $this->get('fork.settings')->set($this->URL->getModule(), 'slideshow_speed', (int) $this->frm->getField('slideshow_speed')->getValue());
                $this->get('fork.settings')->set($this->URL->getModule(), 'animation_speed', (int) $this->frm->getField('animation_speed')->getValue());

                // set optional settings
                $this->get('fork.settings')->set($this->URL->getModule(), 'direction_navigation', (string) ($this->frm->getField('direction_navigation')->getChecked()) ? 'true' : 'false');
                $this->get('fork.settings')->set($this->URL->getModule(), 'control_navigation', (string) ($this->frm->getField('control_navigation')->getChecked()) ? 'true' : 'false');
                $this->get('fork.settings')->set($this->URL->getModule(), 'thumbnail_navigation', (string) ($this->frm->getField('thumbnail_navigation')->getChecked()) ? 'true' : '');
                $this->get('fork.settings')->set($this->URL->getModule(), 'keyboard', (string) ($this->frm->getField('keyboard')->getChecked()) ? 'true' : 'false');
                $this->get('fork.settings')->set($this->URL->getModule(), 'mousewheel', (string) ($this->frm->getField('mousewheel')->getChecked()) ? 'true' : 'false');
                $this->get('fork.settings')->set($this->URL->getModule(), 'touch', (string) ($this->frm->getField('touch')->getChecked()) ? 'true' : 'false');
                $this->get('fork.settings')->set($this->URL->getModule(), 'randomize', (string) ($this->frm->getField('randomize')->getChecked()) ? 'true' : 'false');
                $this->get('fork.settings')->set($this->URL->getModule(), 'auto_animate', (string) ($this->frm->getField('auto_animate')->getChecked()) ? 'true' : 'false');
                $this->get('fork.settings')->set($this->URL->getModule(), 'animation_loop', (string) ($this->frm->getField('animation_loop')->getChecked()) ? 'true' : 'false');

                // trigger event
                BackendModel::triggerEvent($this->getModule(), 'after_saved_settings');

                // redirect to the settings page
                $this->redirect(BackendModel::createURLForAction('Settings') . '&report=saved');
            }
        }
    }
}
