<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://creatotech.com/
 * @since      1.0.0
 *
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/admin/partials
 */
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'creatonews'; // Replace 'your_custom_table' with your actual table name
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

    /**
    * get the option table value for input data
    **/
    $news_option_url_get = get_option('news_source_url');
    $unserlize_links = unserialize($news_option_url_get);

    $site_1 = $file_site_2 = '';
    if(!empty($unserlize_links['site_1']) ){
        $site_1 = $unserlize_links['site_1'];
    }

    $file_site_1 = file_get_html($site_1);

    if(!empty($file_site_1)){

        /******** site_1 https://news.mit.edu/topic/artificial-intelligence2 ********/
        $articles1 = $file_site_1->find('.page-term--views--list-item');
        foreach($articles1 as $article1_data){
            $item['title'] = $article1_data->find('span[itemprop="name headline"]', 0)->plaintext;
            //$item['intro'] = $article1_data->find('.term-page--news-article--item--dek span', 0)->plaintext;
            $image_data = $article1_data->find('.term-page--news-article--item--cover-image img', 0)->getAttribute('data-src');
            $item['new_img'] = 'https://news.mit.edu/'.$image_data;
            
            $post_link = $article1_data->find('.term-page--news-article--item--cover-image a', 0)->href;
            $full_link = file_get_html('https://news.mit.edu/'.$post_link);
            $item['intro'] = $full_link->find('.paragraph.paragraph--type--content-block-text.paragraph--view-mode--default', 0)->innertext;
           
            $items[] = $item;
        }

        if($table_exists){
            foreach($items as $data){
                //$content_text1 = str_replace('<![CDATA[', '', $data['content']);
                // $content = str_replace(']]>', '', $content_text1);
    
                $intro_text1 = str_replace('<![CDATA[', '', $data['intro']);
                $intro = str_replace(']]>', '', $intro_text1);
    
                // Your data to insert
                $data_to_insert = array(
                    'title' => $data['title'],
                    'short_text' => trim($intro),
                    'new_img' => $data['new_img'],
                    //'fulltext' => trim($data['full_text'])
                );
    
                // Check if the title already exists
                $title_exists = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT COUNT(*) FROM {$wpdb->prefix}creatonews WHERE title = %s",
                        $data_to_insert['title']
                    )
                );
    
                if ($title_exists == 0) {
                    $wpdb->insert(
                        $table_name,
                        $data_to_insert
                    );
                } else {
                  // echo 'Title already exists in the database.';
                }
            }
        }
    }
?>