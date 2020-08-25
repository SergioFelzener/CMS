<?php

function blockspare_blocks_render_block_core_latest_posts_list($attributes)
{
    
    $unq_class = mt_rand(100000,999999);
    $blockuniqueclass = '';
    
    if(!empty($attributes['uniqueClass'])){
        $blockuniqueclass = $attributes['uniqueClass'];
    }else{
        $blockuniqueclass = 'blockspare-list-'.$unq_class;
    }
    

    $categories = isset($attributes['categories']) ? $attributes['categories'] : '';

    /* Setup the query */
    $grid_query = new WP_Query(
        array(
            'posts_per_page' => $attributes['postsToShow'],
            'post_status' => 'publish',
            'order' => $attributes['order'],
            'orderby' => $attributes['orderBy'],
            'cat' => $categories,
            'offset' => $attributes['offset'],
            'post_type' => $attributes['postType'],
            'ignore_sticky_posts' => 1,
        )
    );

    $blockspare_content_markup = '';

    /* Start the loop */
    if ($grid_query->have_posts()) {

        while ($grid_query->have_posts()) {
            $grid_query->the_post();

            /* Setup the post ID */
            $post_id = get_the_ID();

            /* Setup the featured image ID */
            $post_thumb_id = get_post_thumbnail_id($post_id);
    
            $has_img_class = '';
    
            if(!$post_thumb_id){
                $has_img_class = "post-has-no-image";
            }

            /* Setup the post classes */
            $post_classes = 'blockspare-post-single ';

            /* Add sticky class */
            if (is_sticky($post_id)) {
                $post_classes .= ' sticky';
            } else {
                $post_classes .= null;
            }

            /* Join classes together */
            //$post_classes = join(' ', get_post_class($post_classes, $post_id));


            if ($attributes['enableBackgroundColor']) {
                $post_classes .= 'has-background';
            }
            
            

            /* Start the markup for the post */
            $blockspare_content_markup .= sprintf(
                '<div id="post-%1$s" class="%2$s  %3$s">',
                esc_attr($post_id),
                esc_attr($post_classes),
                $has_img_class
                

            );

            /* Get the featured image */
            if (isset($attributes['displayPostImage']) && $attributes['displayPostImage'] && $post_thumb_id) {


                if (!empty($attributes['imageSize'])) {
                    $post_thumb_size = $attributes['imageSize'];
                }

                if (has_post_thumbnail($post_id)) {
                    /* Output the featured image */
                    $blockspare_content_markup .= sprintf(
                        '<figure class="blockspare-post-img"><a href="%1$s" rel="bookmark" aria-hidden="true" tabindex="-1" >%2$s</a></figure>',
                        esc_url(get_permalink($post_id)),
                        wp_kses_post(wp_get_attachment_image($post_thumb_id, $post_thumb_size))

                    );
                }

            }


            /* Wrap the text content */
            $blockspare_content_markup .= sprintf(
                '<div class="blockspare-post-content %1$s">',
                $attributes['contentOrder']
            );

            $blockspare_content_markup .= sprintf(
                '<header class="blockspare-block-post-grid-header">'
            );
            if ($attributes['displayPostCategory']) {
                $blockspare_content_markup .= sprintf(
                    '<div class="blockspare-post-category" >%1$s</div>',
                    get_the_category_list(esc_html__(' ', 'blockspare'), '', $post_id)
                    
                );
            }


            /* Get the post title */
            $title = get_the_title($post_id);

            if (!$title) {
                $title = __('Untitled', 'blockspare');
            }

            if (isset($attributes['displayPostTitle']) && $attributes['displayPostTitle']) {

                if (isset($attributes['postTitleTag'])) {
                    $post_title_tag = $attributes['postTitleTag'];
                } else {
                    $post_title_tag = 'h4';
                }

                $blockspare_content_markup .= sprintf(
                    '<%3$s class="blockspare-block-post-grid-title"><a href="%1$s" class="blockspare-title-link" rel="bookmark"><span>%2$s</span></a></%3$s>',
                    esc_url(get_permalink($post_id)),
                    esc_html($title),
                    esc_attr($post_title_tag)
                );
            }

            if (isset($attributes['postType']) && ($attributes['postType'] === 'post') && (isset($attributes['displayPostAuthor']) || isset($attributes['displayPostDate']))) {
                /* Wrap the byline content */
                $blockspare_content_markup .= sprintf(
                    '<div class="blockspare-block-post-grid-byline">'
                );

                /* Get the post author */
                if (isset($attributes['displayPostAuthor']) && $attributes['displayPostAuthor']) {
                    $blockspare_content_markup .= sprintf(
                        '<div class="blockspare-block-post-grid-author"><a class="blockspare-text-link" href="%2$s" itemprop="url" rel="author"><span itemprop="name">%1$s</span></a></div>',
                        esc_html(get_the_author_meta('display_name', get_the_author_meta('ID'))),
                        esc_url(get_author_posts_url(get_the_author_meta('ID')))
                        
                    );
                }

                /* Get the post date */
                if (isset($attributes['displayPostDate']) && $attributes['displayPostDate']) {
                    $blockspare_content_markup .= sprintf(
                        '<time " datetime="%1$s" class="blockspare-block-post-grid-date" itemprop="datePublished">%2$s</time>',
                        esc_attr(get_the_date('c', $post_id)),
                        esc_html(get_the_date('', $post_id))
                        
                    );
                }

                /* Close the byline content */
                $blockspare_content_markup .= sprintf(
                    '</div>'
                );
            }

            /* Close the header content */
            $blockspare_content_markup .= sprintf(
                '</header>'
            );

            /* Wrap the excerpt content */
            $blockspare_content_markup .= sprintf(
                '<div class="blockspare-block-post-grid-excerpt">'
                

            );

            /* Get the excerpt */

            global $post;


            $excerpt = blockspare_post_excerpt($post_id, $post);

            if (!$excerpt) {
                $excerpt = null;
            }

            if (!empty($excerpt)) {
                $excerpt = explode(' ', $excerpt);
                $trim_to_length = (int)$attributes['excerptLength'] ? (int)$attributes['excerptLength'] : 55;
                if (count($excerpt) > $trim_to_length) {
                    $excerpt = implode(' ', array_slice($excerpt, 0, $trim_to_length)) . '...';
                } else {
                    $excerpt = implode(' ', $excerpt);
                }

            }


            if ((isset($attributes['displayPostExcerpt']) && $excerpt != null) || isset($attributes['displayPostLink'])) {


                /* Wrap the excerpt content */
                $blockspare_content_markup .= sprintf(
                    '<div class="blockspare-block-post-grid-excerpt">'
                    

                );

                if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) {
                    $blockspare_content_markup .= sprintf(
                        '<div class="blockspare-block-post-grid-excerpt-content">%s</div>',
                        wp_kses_post($excerpt)
                    );
                }

                /* Get the read more link */
                if (isset($attributes['displayPostLink']) && $attributes['displayPostLink']) {
                    $blockspare_content_markup .= sprintf(
                        '<p><a  class="blockspare-block-post-grid-more-link blockspare-text-link" href="%1$s" rel="bookmark">%2$s <span class="screen-reader-text">%3$s</span></a></p>',
                        esc_url(get_permalink($post_id)),
                        esc_html($attributes['readMoreText']),
                        esc_html($title)
                    );
                }

                /* Close the excerpt content */
                $blockspare_content_markup .= sprintf(
                    '</div>'
                );

            }

            /* Close the text content */
            $blockspare_content_markup .= sprintf(
                '</div>'
            );
            
            $blockspare_content_markup .= sprintf(
                '</div>'
            );

            /* Close the post */
            $blockspare_content_markup .= "</div>\n";
        }

        /* Restore original post data */
        wp_reset_postdata();

        /* Build the block classes */
        $class = "wp-block-blockspare-block-blockspare-latest-posts blockspare-post-wrap align{$attributes['align']}";

        if (isset($attributes['className'])) {
            $class .= ' ' . $attributes['className'];
        }
        $col_class = '';

        if ($attributes['design'] === 'blockspare-is-grid') {
            $col_class = 'column-' . $attributes['columns'];
        }


        $list_layout_class = $attributes['design'];

        /* Layout orientation class */
        $grid_class = $blockuniqueclass .' blockspare-latest-post-wrap blockspare-is-list ' . $list_layout_class;


        /* Post grid section title */
        if (isset($attributes['displaySectionTitle']) && $attributes['displaySectionTitle'] && !empty($attributes['sectionTitle'])) {
            if (isset($attributes['sectionTitleTag'])) {
                $section_title_tag = $attributes['sectionTitleTag'];
            } else {
                $section_title_tag = 'h4';
            }

            $section_title = '<' . esc_attr($section_title_tag) . '>' . esc_html($attributes['sectionTitle']) . '</' . esc_attr($section_title_tag) . '>';
        } else {
            $section_title = null;
        }

        /* Post grid section tag */
        if (isset($attributes['sectionTag'])) {
            $section_tag = $attributes['sectionTag'];
        } else {
            $section_tag = 'section';
        }

        /* Output the post markup */
        $block_content = sprintf(
            '<%1$s class="%2$s"><div class="%4$s">%5$s</div></%1$s>',
            $section_tag,
            esc_attr($class),
            $section_title,
            esc_attr($grid_class),
            $blockspare_content_markup


        );
    
        $block_content .= '<style type="text/css">';
        $block_content .= ' .' . $blockuniqueclass . '.blockspare-latest-post-wrap{
        margin-top:' . $attributes['marginTop'] . 'px;
        margin-bottom:' . $attributes['marginBottom'] . 'px;
        }';
    
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-post-category{
        color:' . $attributes['linkColor'] . ';
        }';
    
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-block-post-grid-author a span{
        color:' . $attributes['linkColor'] . ';
        }';
    
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-text-link a span{
        color:' . $attributes['linkColor'] . ';
        }';
    
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-block-post-grid-title a span{
            color: ' . $attributes['postTitleColor'] . ';
            font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
            font-family: ' . $attributes['titleFontFamily'] . ';
            font-weight: ' . $attributes['titleFontWeight'] . ';
        }';
    
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-block-post-grid-date{
        color:' . $attributes['generalColor'] . ';
        }';
    
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-block-post-grid-excerpt{
        color:' . $attributes['generalColor'] . ';
        }';
    
    
    
    
        if ($attributes['design'] != 'blockspare-list-layout-4' && $attributes['design'] != 'blockspare-list-layout-5' && $attributes['design'] != 'blockspare-list-layout-6') {
            $block_content .= ' .' . $blockuniqueclass . ' .blockspare-post-single{
        border: 1px solid ' . $attributes['borderColor'] . ';
        background-color:' . $attributes['backGroundColor'] . ';
        border-radius:' . $attributes['borderRadius'] . ';
        }';
        
        }
    
        if ($attributes['enableBoxShadow']) {
            $block_content .= ' .' . $blockuniqueclass . ' .blockspare-post-single{
            box-shadow = ' . $attributes['xOffset'] . 'px ' . $attributes['yOffset'] . 'px ' . $attributes['blur'] . 'px ' . $attributes['spread'] . 'px ' . $attributes['shadowColor'] . ';
            }';
        }
    
    
        $block_content .= '@media (max-width: 1025px) { ';
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-block-post-grid-title a{
        font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
        }';
        $block_content .= '}';
    
    
        $block_content .= '@media (max-width: 768px) { ';
        $block_content .= ' .' . $blockuniqueclass . ' .blockspare-block-post-grid-title a{
        font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
        }';
        $block_content .= '}';
    
    
        $block_content .= '</style>';
        return $block_content;
    }
}

/**
 * Registers the post grid block on server
 */
function blockspare_blocks_register_block_core_latest_posts_list()
{

    /* Check if the register function exists */
    if (!function_exists('register_block_type')) {
        return;
    }

    ob_start();
    include BLOCKSPARE_PLUGIN_DIR . '/src/blocks/latest-posts-list/block.json';

    $metadata = json_decode(ob_get_clean(), true);

    /* Block attributes */
    register_block_type(
        'blockspare/blockspare-latest-posts-list',
        array(
            'attributes' => $metadata['attributes'],
            'render_callback' => 'blockspare_blocks_render_block_core_latest_posts_list',
        )
    );
}

add_action('init', 'blockspare_blocks_register_block_core_latest_posts_list');


/**
 * Create API fields for additional info
 */
function blockspare_blocks_post_register_rest_fields()
{

    register_rest_field('post', 'featured_image_urls',
        array(
            'get_callback' => 'blockspare_featured_image_urls',
            'update_callback' => null,
            'schema' => array(
                'description' => __('Different sized featured images', 'blockspare'),
                'type' => 'array',
            ),
        )
    );

    // Excer

    /* Add author info */
    register_rest_field(
        'post',
        'author_info',
        array(
            'get_callback' => 'blockspare_blocks_get_author_infos',
            'update_callback' => null,
            'schema' => null,
        )
    );

    /* Add author info */
    register_rest_field(
        'post',
        'category_info',
        array(
            'get_callback' => 'blockspare_blocks_get_category_infos',
            'update_callback' => null,
            'schema' => null,
        )
    );

    /* Add author info */
    register_rest_field(
        'post',
        'tag_info',
        array(
            'get_callback' => 'blockspare_blocks_get_tag_infos',
            'update_callback' => null,
            'schema' => null,
        )
    );
}

add_action('rest_api_init', 'blockspare_blocks_post_register_rest_fields');


/**
 * Get author info for the rest field
 *
 * @param String $object The object type.
 * @param String $field_name Name of the field to retrieve.
 * @param String $request The current request object.
 */
function blockspare_blocks_get_author_infos($object, $field_name, $request)
{
    /* Get the author name */
    $author_data['display_name'] = get_the_author_meta('display_name', $object['author']);

    /* Get the author link */
    $author_data['author_link'] = get_author_posts_url($object['author']);

    /* Return the author data */
    return $author_data;
}

function blockspare_blocks_get_category_infos($object, $field_name, $request)
{


    return get_the_category_list(esc_html__(' ', 'blockspare'), '', $object['id']);

}

function blockspare_blocks_get_tag_infos($object, $field_name, $request)
{

    ob_start();
    $cate_name = '';
    if (!empty($object)) {
        foreach ($object['categories'] as $cat_id) {
            $cate_name = get_cat_name($cat_id);
        }
    }
    ob_clean();
    return $cate_name;

}

if (!function_exists('blockspare_featured_image_urls')) {
    /**
     * Get the different featured image sizes that the blog will use.
     * Used in the custom REST API endpoint.
     *
     * @since 1.7
     */
    function blockspare_featured_image_urls($object, $field_name, $request)
    {
        return blockspare_featured_image_urls_from_url(!empty($object['featured_media']) ? $object['featured_media'] : '');
    }
}


if (!function_exists('blockspare_featured_image_urls_from_url')) {
    /**
     * Get the different featured image sizes that the blog will use.
     *
     * @since 2.0
     */
    function blockspare_featured_image_urls_from_url($attachment_id)
    {

        $image = wp_get_attachment_image_src($attachment_id, 'full', false);
        $sizes = get_intermediate_image_sizes();

        $imageSizes = array(
            'full' => is_array($image) ? $image : '',
        );

        foreach ($sizes as $size) {
            $imageSizes[$size] = is_array($image) ? wp_get_attachment_image_src($attachment_id, $size, false) : '';
        }

        return $imageSizes;
    }
}


if (!function_exists('blockspare_post_excerpt')) {
    /**
     * Get the post excerpt.
     *
     * @since 1.7
     */
    function blockspare_post_excerpt($object)
    {
        return blockspare_get_excerpt($object['id']);
    }
}

if (!function_exists('blockspare_get_excerpt')) {
    /**
     * Get the excerpt.
     *
     * @since 1.7
     */
    function blockspare_get_excerpt($post_id, $post = null)
    {
        $excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $post_id, 'display'));
        if (!empty($excerpt)) {
            return $excerpt;
        }

        $max_excerpt = 100; // WP default is 55.

        if (!empty($post['post_content'])) {
            return apply_filters('the_excerpt', wp_trim_words($post['post_content'], $max_excerpt));
        }
        $post_content = apply_filters('the_content', get_post_field('post_content', $post_id));
        return apply_filters('the_excerpt', wp_trim_words($post_content, $max_excerpt));
    }
}

    
