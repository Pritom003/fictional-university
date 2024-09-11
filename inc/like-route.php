<?php 
add_action('rest_api_init', 'universityLikeRoute');
function universityLikeRoute() {
    register_rest_route('university/v1', 'managelike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));
    register_rest_route('university/v1', 'managelike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {
    if (is_user_logged_in()) {
        error_log('User is logged in');
        $professor = sanitize_text_field($data['professorId']);
        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id_',
                    'compare' => '=',
                    'value' => $professor
                )
            )
        ));

        if ($existQuery->found_posts == 0 AND get_post_type($professor)=='professor') {
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => 'our 5 php test',
                'meta_input' => array(
                    'liked_professor_id_' => $professor
                )
            ));
        } else {
            die('Invalid professor ID.');
        }
    } else {
        die('Only logged-in users can like a professor.');
    }
}

function deleteLike() {
    return 'Deleted like';
}
