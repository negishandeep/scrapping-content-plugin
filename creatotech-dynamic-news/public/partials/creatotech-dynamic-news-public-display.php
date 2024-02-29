<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://https://creatotech.com/
 * @since      1.0.0
 *
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="news_dynamic_row">
    <?php 
		global $wpdb;
		$table_name = $wpdb->prefix . 'creatonews';
		$post_id = array();
		$sql_post = "SELECT * FROM $table_name";
		$results_post = $wpdb->get_results( $sql_post );
		
		if(!empty($results_post)){
			foreach($results_post as $post){
				$post_id[] = $post->id;
			}
		}
	
		$data_table_save_get = get_option('data_table_save');
		$get_save_data = unserialize($data_table_save_get);

		if($get_save_data == 'creatonews'){
			// Define pagination parameters
	        $page = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 1;
	        $per_page = 9; // Adjust this value based on the number of items you want to display per page.
	        $offset = ( $page - 1 ) * $per_page;
	        
	        // Your custom table name
	        //$table_name = $wpdb->prefix . 'creatonews';
			
			// detail page code start
			if(!empty($_GET['newspost']) && isset($_GET['newspost'])){
				$detail_id = $_GET['newspost'];
				$query_det = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $detail_id);
				$results_det = $wpdb->get_results( $query_det );
				
				if(!empty($results_det)){
					echo '<div class="single_new_sec">';
					foreach($results_det as $single_detils){
						echo '<h2>'.$single_detils->title.'</h2>';
						echo '<img src="'.$single_detils->new_img.'" class="sin_img">';
						echo '<div class="sing_content">';
							echo $single_detils->short_text;
						echo '</div>';
					}
					// single data get end
					$currentKey = array_search($detail_id, $post_id);
					
					// Get the next element
					$nextKey = $currentKey + 1;
					$nextElement = isset($post_id[$nextKey]) ? $post_id[$nextKey] : null;

					// Get the previous element
					$prevKey = $currentKey - 1;
					$prevElement = isset($post_id[$prevKey]) ? $post_id[$prevKey] : null;
					
						echo '<ul class="details_next_prev">';
							if(!empty($prevElement)){
								echo '<li><a href="?newspost='.$prevElement.'"><span><<</span>Previous</a></li>';
							}
							if(!empty($nextElement)){
								echo '<li><a href="?newspost='.$nextElement.'">Next>></a></li>';
							}
						echo '</ul>';
					
					echo '</div>';
				}
			// detail page code end
			}else{
				// Build and execute the SELECT query
				$sql = $wpdb->prepare(
					"SELECT * FROM {$table_name} ORDER BY id DESC LIMIT %d OFFSET %d",
					$per_page,
					$offset
				);
				$results = $wpdb->get_results( $sql );
				
				// Retrieve total number of items for pagination
				$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name}" );
				
				// Add pagination links
				$page_links = paginate_links( array(
					'base' => add_query_arg( 'post', '%#%' ),
					'format' => '',
					'prev_text' => __( '&laquo; Previous' ),
					'next_text' => __( 'Next &raquo;' ),
					'total' => ceil( $total_items / $per_page ),
					'current' => $page,
				) );
				
				// Display your results
				if ( $results ) {
					foreach ( $results as $result ) {
						
						$originalText = $result->short_text;
						$maxCharacters = 150;
						if (strlen($originalText) > $maxCharacters){
							$limitedText = substr($originalText, 0, $maxCharacters) . '...';
						} else {
							$limitedText = $originalText;
						}
						echo '<div class="news_dynamic_col"><a href="?newspost='.$result->id.'"><div class="news_d_c">';
							if(!empty($result->new_img)){
								echo '<img src="'.$result->new_img.'" class="news_img">';
							}
							echo '<div class="d_n_content">';
								if(!empty($result->title)){
									echo '<h2>'.$result->title.'</h2>';
								}
								if(!empty($result->short_text)){
									echo strip_tags($limitedText);
								}
							echo '</div>';
						echo '</div></a></div>';
					}
				}
				
				// Display pagination links
				echo '<div class="creato_news_pagination">' . $page_links . '</div>';
			}
		}else if($get_save_data == 'post_type'){
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(  
                'post_type' => 'dynamic_news',
                'post_status' => 'publish',
                'posts_per_page' => 9,
                'order' => 'ASC',
                'paged' => $paged,
            );

            $loop = new WP_Query( $args );
            if($loop->have_posts()){
                while ( $loop->have_posts() ) : $loop->the_post();
                    $id = get_the_ID();

					echo '<div class="news_dynamic_col"><div class="news_d_c">';
						if(!empty(has_post_thumbnail($id))){
							echo '<a href="'.get_permalink($id).'"><img src="'.get_the_post_thumbnail_url($id).'" class="news_img"></a>';
						}
						echo '<div class="d_n_content">';
	                        echo '<h2>'.get_the_title($id).'</h2>';
	                        echo '<p>'.get_the_excerpt($id).'</p>';
						echo '</div>';
					echo '</div></div>'; 
                endwhile;
                wp_reset_postdata();
                echo '<div class="creato_news_pagination">';
                $big = 999999999; // need an unlikely integer
                 echo paginate_links( array(
                    'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, get_query_var('paged') ),
                    'total' => $loop->max_num_pages
                ) );
                echo '</div>';
            }
		} 
    ?>
</div>