<?php
// Hook into the REST API initialization action
// Hook into the REST API initialization action
add_action('rest_api_init', 'universityRegisterSearch');

// Function to register the custom REST API route
function universityRegisterSearch() {
    // Register a new REST route with the endpoint 'university/v1/search'
    register_rest_route('university/v1', 'search', array(
        // Allow only GET requests
        'methods' => WP_REST_SERVER::READABLE,
        // Callback function to handle the search query
        'callback' => 'universitySearchResult',
    ));
}

// Callback function to handle the search query and return results
function universitySearchResult($data) {
    // Create a new WP_Query to search across multiple post types
    $mainQuery = new WP_Query(array(
        // Specify the post types to search within
        'post_type' => array('post', 'page', 'professor', 'campus', 'event', 'program'),
        // Sanitize and use the search term provided in the query parameter
        's' => sanitize_text_field($data['term']),
    ));

    // Initialize an empty array to store the search results
    $result = array(
        'generalInfo' => array(), 
        'professors' => array(),  
        'campuses' => array(),    
        'programs' => array(),    
        'events' => array(),      
    );

    // Loop through the search results
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post(); // Set up post data for the current post

        // Check if the post type is 'post' or 'page'
        if (get_post_type() == 'post' || get_post_type() == 'page') {
            // Add the title and link to the 'generalInfo' array
            array_push($result['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }

        // Check if the post type is 'program'
        if (get_post_type() == 'program') {
            // Add the title and link to the 'programs' array
            array_push($result['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID()
            ));
        }

        // Check if the post type is 'event'
        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);
            array_push($result['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description 
            ));
        }

        // Check if the post type is 'professor'
        if (get_post_type() == 'professor') {
            array_push($result['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ));
        }

        // Check if the post type is 'campus'
        if (get_post_type() == 'campus') {
            array_push($result['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }
    }

    // If there are programs in the search results, find related professors
    if ($result['programs']) {
        $programMetaQuery = array('relation' => 'OR');
        foreach ($result['programs'] as $item) {
            array_push($programMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ));
        }

        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor','event'),
            'meta_query' => $programMetaQuery
        ));

        while ($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);
                array_push($result['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description 
                ));
            }
            if (get_post_type() == 'professor') {
                array_push($result['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                ));
            }
        }

        $result['professors'] = array_values(array_unique(
            $result['professors'], SORT_REGULAR));

        $result['events'] = array_values(array_unique(
            $result['events'], SORT_REGULAR));
    }

    // Return the structured search results
    return $result;
}
