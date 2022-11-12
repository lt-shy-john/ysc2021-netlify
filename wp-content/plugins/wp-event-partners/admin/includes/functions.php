<?php

function wep_pass_query_parameters($postPerPage = 5, $postType, $order_by='title', $order='DESC', $tax_query=false, $taxonomy='', $terms=''){

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    if($tax_query){
        $args = [
            'posts_per_page' => $postPerPage,
            'post_type' => $postType,
            'orderby' => $order_by,
            'order' => $order,
            'post_status'=>'publish',
            'paged'=>$paged,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $terms
                ]
            ]
        ];
    }else{
        $args = [
            'posts_per_page' => $postPerPage,
            'post_type' => $postType,
            'orderby' => $order_by,
            'order' => $order,
            'post_status'=>'publish',
            'paged'=>$paged,

        ];

    }
    return $args;
}

