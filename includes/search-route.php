<?php

add_action('rest_api_init', 'ticoRegisterSearch');

function ticoRegisterSearch() {
    register_rest_route('tico/v1', 'search', array(
        // 'methods' => 'GET',
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'ticoSearchResults'
    ));
}

function ticoSearchResults($data) {
    $mainQuery = new WP_Query(array(
		// 'post_type' => 'designer',
        'post_type' => array('designer', 'post', 'page', 'product', 'pattern', 'store'),
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'generalInfo' => array(),
        'designers' => array(),
        'patterns' => array(),
        'products' => array(),
        'stores' => array()
    );

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }
        if (get_post_type() == 'designer') {
            array_push($results['designers'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
				'image' => get_the_post_thumbnail_url(0, 'designerLandscape'), // 0 means current post, size
				'id' => get_the_id()
            ));
        }
        if (get_post_type() == 'pattern') {
			// RELACIONAMENTO INVERSO, buscar por pattern e encontrar o designer
			$relatedDesigners = get_field('related_designer');
			if ($relatedDesigners) {
				foreach($relatedDesigners as $designer) {
					array_push($results['designers'], array(
						'title' => get_the_title($designer),
						'permalink' => get_the_permalink($designer),
						'image' => get_the_post_thumbnail_url($designer, 'designerLandscape'), // 0 means current post, size
						'id' => get_the_id($designer)
					));
				}
			}

			// RELACIONAMENTO INVERSO, buscar por pattern e encontrar os products
			$relatedProducts = get_field('related_products');
			if ($relatedProducts) {
				foreach($relatedProducts as $product) {
					array_push($results['products'], array(
						'title' => get_the_title($product),
						'permalink' => get_the_permalink($product),
						'id' => get_the_ID($product)
					));
				}
			}
			/// está enviando hipo repetido com o ID da pattern
			
			$eventDate = new DateTime(get_field('event_date'));
			$description = null;
			if(has_excerpt()) {
				$description = get_the_excerpt();
			} else {
				$description = wp_trim_words(get_the_content(), 18);
			}

            array_push($results['patterns'], array(
                'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'month' => $eventDate->format('M'),
				'day' => $eventDate->format('d'),
				'description' => $description
            ));
        }
        if (get_post_type() == 'product') {
			// RELACIONAMENTO INVERSO, buscar por produto e encontrar a store
			$relatedStores = get_field('related_store');
			if ($relatedStores) {
				foreach($relatedStores as $store) {
					array_push($results['stores'], array(
						'title' => get_the_title($store),
						'permalink' => get_the_permalink($store),
						'id' => get_the_id($store)
					));
				}
			}
			
            array_push($results['products'], array(
                'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'id' => get_the_ID()
            ));
        }
        if (get_post_type() == 'store') {
            array_push($results['stores'], array(
                'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'id' => get_the_id()
            ));
        }
	}

	// RELATED FIELDS

	// Buscar por Designer, deve retornar as Patterns relacionadas a ele
	if ($results['designers']) {
		$designersMetaQuery = array('relation' => 'OR');
		foreach($results['designers'] as $item) {
			array_push($designersMetaQuery, array(
				'key' => 'related_designer',
				'compare' => 'LIKE',
				'value' => '"' . $item['id'] . '"'
			));
		}
	
		$designerRelationshipQuery = new WP_Query(array(
			'post_type' => 'pattern',
			'meta_query' => $designersMetaQuery
		));
	
		while($designerRelationshipQuery->have_posts()) {
			$designerRelationshipQuery->the_post();
			$eventDate = new DateTime(get_field('event_date'));
			$description = null;
			if(has_excerpt()) {
				$description = get_the_excerpt();
			} else {
				$description = wp_trim_words(get_the_content(), 18);
			}
	
			array_push($results['patterns'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'month' => $eventDate->format('M'),
				'day' => $eventDate->format('d'),
				'description' => $description
			));
		}
		$results['patterns'] = array_values(array_unique($results['patterns'], SORT_REGULAR));
	}
	
	// Buscar por Stores, deve retornar os Products relacionados a ela
	if ($results['stores']) {
		$storesMetaQuery = array('relation' => 'OR');
		foreach($results['stores'] as $item) {
			array_push($storesMetaQuery, array(
				'key' => 'related_store',
				'compare' => 'LIKE',
				'value' => '"' . $item['id'] . '"'
			));
		}
	
		$storeRelationshipQuery = new WP_Query(array(
			'post_type' => 'product',
			'meta_query' => $storesMetaQuery
		));
	
		while($storeRelationshipQuery->have_posts()) {
			$storeRelationshipQuery->the_post();
			array_push($results['products'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
		}
		$results['products'] = array_values(array_unique($results['products'], SORT_REGULAR));
	}

	// Buscar por Product, deve retornar as Patterns relacionadas a ele
	if ($results['products']) {
		$productsMetaQuery = array('relation' => 'OR');
		foreach($results['products'] as $item) {
			array_push($productsMetaQuery, array(
				'key' => 'related_products',
				'compare' => 'LIKE',
				'value' => '"' . $item['id'] . '"'
			));
		}
	
		$productRelationshipQuery = new WP_Query(array(
			'post_type' => 'pattern',
			'meta_query' => $productsMetaQuery
		));
	
		while($productRelationshipQuery->have_posts()) {
			$productRelationshipQuery->the_post();
			$eventDate = new DateTime(get_field('event_date'));
			$description = null;
			if(has_excerpt()) {
				$description = get_the_excerpt();
			} else {
				$description = wp_trim_words(get_the_content(), 18);
			}
	
			array_push($results['patterns'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'month' => $eventDate->format('M'),
				'day' => $eventDate->format('d'),
				'description' => $description
			));
		}
		$results['patterns'] = array_values(array_unique($results['patterns'], SORT_REGULAR));
	}

    return $results;
}