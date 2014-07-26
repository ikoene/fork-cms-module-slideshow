{*
    variables that are available:
    - {$gallery}: contains an array with all galleries, each element contains data about the gallery
*}

{option:galleries}

    {iteration:galleries}

        {* Title *}
            <h3>
                <a href="{$full_url}/{$galleries.meta_url}">{$galleries.title}</a>
            </h3>

            <ul>
                <li>
                {* Meta *}
                {$galleries.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}

                {* Category*}
                {$lblIn} {$lblThe} {$lblCategory} <a href="{$full_url_category}/{$galleries.category_meta_url}" title="{$galleries.category_meta_title}">
                {$galleries.category_title}</a>

                {* Description *}
                {option:galleries.description}{$galleries.description}{/option:galleries.description}
                </li>
            </ul>

            {option:galleries.filename}
                <a href="{$full_url}/{$galleries.meta_url}">
                    <img src="/src/Frontend/Files/userfiles/images/slideshow/thumbnails/{$galleries.filename}" alt="{$galleries.title}" />
                </a>
            {/option:galleries.filename}

    {/iteration:galleries}

{/option:galleries}
