<?php

add_action('rest_api_init', 'ticoRegisterLink');

function ticoRegisterLink() {
    register_rest_route('tico/v1', 'manageLike', array(
        'methods' => WP_REST_SERVER::CREATABLE,
        'callback' => 'createLike'
    ));

    register_rest_route('tico/v1', 'manageLike', array(
        'methods' => WP_REST_SERVER::DELETABLE,
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {
    if (is_user_logged_in()) {
        $liked_designer_id = sanitize_text_field($data['designerId']);

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_designer_id',
                    'compare' => '=',
                    'value' => $liked_designer_id
                )
            )
        ));

        if ($existQuery->found_posts == 0 AND get_post_type($liked_designer_id) == 'designer') {
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'meta_input' => array(
                    'liked_designer_id' => $liked_designer_id
                )
            ));
        } else {
            die("Invalid designer id");
        }
    } else {
        die("Only logged in users can create a like.");
    }
}

function deleteLike($data) {
	$likeId = sanitize_text_field($data['like']);
	if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
		wp_delete_post($likeId, true);
		return "Success. Like deleted.";
	} else {
		die("You do not have permission to delete that.");
	}
}