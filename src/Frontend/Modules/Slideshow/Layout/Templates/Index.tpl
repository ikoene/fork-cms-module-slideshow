{*
    variables that are available:
    - {$galleries}: contains an array with all galleries, each element contains data about the gallery
*}

{option:galleries}

    {iteration:galleries}

        {* Title *}
            <h3>
                <a href="{$full_url}/{$galleries.url}">{$galleries.title}</a>
            </h3>

            <ul>
                <li>
                {* Meta *}
                {$galleries.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}

                {* Category*}
                {$lblIn} {$lblThe} {$lblCategory} <a href="{$full_url_category}/{$galleries.category_url}" title="{$galleries.category_title}">{$galleries.category_title}</a>

                {* Description *}
                {option:galleries.description}{$galleries.description}{/option:galleries.description}
                </li>
            </ul>

            {option:galleries.filename}
                <a href="{$full_url}/{$galleries.url}">
                    <img src="/src/Frontend/Files/slideshow/{$galleries.filename}" alt="{$galleries.title}" />
                </a>
            {/option:galleries.filename}

    {/iteration:galleries}

{/option:galleries}
