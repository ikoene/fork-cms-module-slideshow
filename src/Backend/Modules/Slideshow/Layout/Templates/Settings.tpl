{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblModuleSettings|ucfirst}: {$lblSlideshow}</h2>
</div>

{form:settings}
<div class="box">
        <div class="heading">
            <h3>{$lblModeSlideshowSettings|ucfirst}</h3>
        </div>
        <div class="options">
            <ul class="inputList">
                <li>
                    <label for="settings_per_slide">
                        {$chkSettingsPerSlide} {$lblSettingsPerSlide|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpSettingsPerSlide}
                    </span>
                </li>
            </ul>
        </div>
</div>

    <div class="box horizontal">
        <div class="heading">
            <h3>{$lblGeneralSlideshowSettings|ucfirst}</h3>
        </div>
        <div class="options">
            <p>
                <label for="animation_type">{$lblAnimationType|ucfirst}</label>
                {$ddmAnimationType} {$ddmAnimationTypeError}
            </p>
        </div>
        <div class="options">
            <p>
                <label for="slide_direction">{$lblSlideDirection|ucfirst}</label>
                {$ddmSlideDirection} {$ddmSlideDirectionError}
            </p>
        </div>
        <div class="options">
            <p>
                <label for="slideshow_speed">{$lblSlideshowSpeed|ucfirst}</label>
                {$ddmSlideshowSpeed} {$ddmSlideshowSpeedError}
                <span class="helpTxt">
                {$msgHelpSlideshowSpeed}
                </span>
            </p>
        </div>
        <div class="options">
            <p>
                <label for="animation_duration">{$lblAnimationDuration|ucfirst}</label>
                {$ddmAnimationDuration} {$ddmAnimationDurationError}
                <span class="helpTxt">
                {$msgHelpAnimationDuration}
                </span>
            </p>
        </div>
    </div>

    <div class="box">
        <div class="heading">
            <h3>{$lblNavigationSlideshowSettings|ucfirst}</h3>
        </div>
        <div class="options">
            <ul class="inputList">
                <li>
                    <label for="direct_navigation">
                        {$chkDirectionNavigation} {$lblDirectionNavigation|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpDirectNavigation}
                    </span>
                </li>
                <li>
                    <label for="control_navigation">
                        {$chkControlNavigation} {$lblControlNavigation|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpControlNavigation}
                    </span>
                </li>
                <li id="thumbnailNavigationBox">
                    <label for="thumbnail_navigation">
                        {$chkThumbnailNavigation} {$lblThumbnailNavigation|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpThumbnailNavigation}
                    </span>
                </li>
                <li>
                    <label for="keyboard">
                        {$chkKeyboard} {$lblKeyboard|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpKeyboard}
                    </span>
                </li>
                <li>
                    <label for="mousewheel">
                        {$chkMousewheel} {$lblMousewheel|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpMousewheel}
                    </span>
                </li>
                <li>
                    <label for="touch">
                        {$chkTouch} {$lblTouch|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpTouch}
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="box">
        <div class="heading">
            <h3>{$lblOptionalSlideshowSettings|ucfirst}</h3>
        </div>
        <div class="options">
            <ul class="inputList">
                <li>
                    <label for="random_order">
                        {$chkRandomOrder} {$lblRandomOrder|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpRandomOrder}
                    </span>
                </li>
                <li>
                    <label for="auto_animate">
                        {$chkAutoAnimate} {$lblAutoAnimate|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpAutoAnimate}
                    </span>
                </li>
                <li>
                    <label for="animation_loop">
                        {$chkAnimationLoop} {$lblAnimationLoop|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpAnimationLoop}
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="fullwidthOptions">
        <div class="buttonHolderRight">
            <input id="save" class="inputButton button mainButton" type="submit" name="save" value="{$lblSave|ucfirst}" />
        </div>
    </div>
{/form:settings}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
