<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://creatotech.com/
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
    $data_table_save_get = get_option('data_table_save');

    $get_save_data = unserialize($data_table_save_get);
    $unserlize_links = unserialize($news_option_url_get);

    $site_1 = $site_2 = $file_site_1 = $file_site_2 = '';
    if(!empty($unserlize_links['site_1']) || !empty($unserlize_links['site_2'])){
        $site_1 = $unserlize_links['site_1'];
        $site_2 = $unserlize_links['site_2'];
    }

    /******** option table data updated ***********/
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['my_news_nonce'], 'save_my_url')) {
        
        $news_source_url_seri = serialize($_POST['news_source_url']);
        $data_table_save = serialize($_POST['data_table_save']);
        update_option('data_table_save', $data_table_save);
        $resp = update_option('news_source_url', $news_source_url_seri);
        if($resp == true){
            echo '<div class="updated"><p>Data saved successfully!</p></div>';
        }

        $file_site_1 = file_get_html($site_1);
        $file_site_2 = file_get_html($site_2);

        if(!empty($file_site_1) || !empty($file_site_2)){

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
        
            /******** site_2 https://deepmind.google/discover/blog/ ********/
            /*$articles2 = $file_site_2->find('li.glue-grid__col');
            foreach($articles2 as $article2_data){
                $item['title'] = $article2_data->find('.glue-card__content .glue-headline', 0)->plaintext;
                $item['intro'] = $article2_data->find('.glue-card__description', 0)->plaintext;
                $item['new_img'] = $article2_data->find('.card .glue-card__asset img', 0)->src;

                $post_link1 = $article2_data->find('a.glue-card.card', 0)->href;
                $full_link1 = file_get_html('https://deepmind.google/'.$post_link1);

                $items[] = $item;
            }*/

            if($get_save_data == 'creatonews'){
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
            }else if($get_save_data == 'post_type'){
                // custom post type news insert start
                if(!empty($items)){
                    foreach($items as $pdta){

                        $intro_text1 = str_replace('<![CDATA[', '', $pdta['intro']);
                        $intro = str_replace(']]>', '', $intro_text1);

                        $new_post = array(
                            'post_title'    => $pdta['title'],
                            'post_content'  => trim($intro),
                            'post_status'   => 'publish',
                            'post_author'   => 1, // ID of the author
                            'post_type'     => 'dynamic_news',
                        );

                        // Insert the post into the database
                        $new_post_id = wp_insert_post($new_post);

                        // Check if the post was inserted successfully
                        if ($new_post_id) {

                            $image_url = $pdta['new_img'];

                            require_once(ABSPATH . 'wp-admin/includes/media.php');
                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                            require_once(ABSPATH . 'wp-admin/includes/image.php');

                            // Download and attach the image
                            $image_id = media_sideload_image($image_url, $new_post_id, 'Featured Image for Post', 'id');
                      
                            if (!is_wp_error($image_id)) {
                                $image_set = set_post_thumbnail($new_post_id, $image_id);
                                if($image_set){
                                    //echo 'Featured image set successfully.'.$image_id;
                                }else{
                                    //echo 'Featured image set failed.'.$image_id;
                                }
                            } else {
                                echo 'Error downloading image: ' . $image_id->get_error_message();
                            }

                        } else {
                            echo 'Error adding post.';
                        }
                    }
                }
            // custom post type news insert end
            }
            
        }
    }
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="main_section_news">
    <h2>URL Selection</h2>
    <span class="fonr_short_news">Use this shortcode for fontend <strong> [dynamic_news] <strong></span>
    <form method="post" action="" class="form_news_sec">
        <?php wp_nonce_field('save_my_url', 'my_news_nonce');?>
        <div class="news_input_row">
            <label>Source URL 1<span>Please url add here for example: abc.com</span></label>
            <input type="text" name="news_source_url[site_1]" value="<?php echo $site_1;?>">
        </div>
		<!--
        <div class="news_input_row">
            <label>Source URL 2<span>Please url add here for example: abc.com</span></label>
            <input type="text" name="news_source_url[site_2]" value="<?php echo $site_2;?>">
        </div>
		-->
        <div class="news_input_row">
            <label>Please selcet the dynamic data you want to save<span></span></label>
            <select name="data_table_save">
              <option value="creatonews" <?php if($get_save_data == 'creatonews'){echo 'selected';}?>>Database Custom Table</option>
              <option value="post_type" <?php if($get_save_data == 'post_type'){echo 'selected';}?>>Post Type</option>
            </select>
        </div>
        <div class="news_input_row">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>
</div>