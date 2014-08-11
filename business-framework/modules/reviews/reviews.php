<?php
/**
 * @package Smartest Themes Business Framework
 * @subpackage Reviews
 * Description: Get reviews from visitors, and aggregate ratings and stars for your business in search results. Adds Microdata markup (Schema.org) for rich snippets. Includes Testimonial widget. Optional: pulls aggregaterating to home page. Option to not pull it to home page, and just have a reviews page. Requires Smartest Themes for full functionality.
 */
class SMARTESTReviewsBusiness {

	private static $instance = null;
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	var $dbtable = 'smareviewsb';// @todo consider chnge table name!!!
	var $got_aggregate = false;
	var $options = array();
	var $p = '';
	var $version = '0.0.0';
	var $status_msg = '';
	
	private function __construct() {
		global $wpdb;
		define('IN_SMAR', 1);
		$this->dbtable = $wpdb->prefix . $this->dbtable;
		$themeobject = wp_get_theme();
		$this->version = $themeobject->Version;
		add_action('init', array($this, 'init'));
		add_action('admin_init', array($this, 'admin_init'));
		add_action( 'widgets_init', array($this, 'smartest_reviews_register_widgets'));
		add_action('template_redirect',array($this, 'template_redirect'));
		add_action('admin_menu', array($this, 'addmenu'));
		add_action('wp_ajax_update_field', array($this, 'admin_view_reviews'));
		add_action('save_post', array($this, 'admin_save_post'), 10, 2);
		add_action( 'admin_init', array($this, 'create_reviews_page'));//@note, but for stand-alone plugin hook to after_setup_theme
		add_action('wp_enqueue_scripts', array($this, 'smartestreviews_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'smartestreviews_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_stuff'));
		add_filter( 'the_content', array($this, 'homepage_aggregate_footer') );
    }

	function addmenu() {
		add_options_page(__('Reviews', 'crucible'), __('Reviews', 'crucible'), 'manage_options', 'smar_options', array( $this, 'admin_options'));
		if(get_option('st_add_reviews') == 'true') {       
			add_menu_page(__('Reviews', 'crucible'), __('Reviews', 'crucible'), 'edit_others_posts', 'smar_view_reviews', array($this, 'admin_view_reviews'), 'dashicons-star-filled', 62);
		}
	}

	/**
	* Generates an html link for a review with its page and ID as URL parameters
	*/
    function get_jumplink_for_review($review,$page) {
       $link = get_permalink( get_option('smartestthemes_reviews_page_id') );
        if (strpos($link,'?') === false) {
            $link = trailingslashit($link) . "?smarp=$page#hreview-$review->id";
        } else {
            $link = $link . "&smarp=$page#hreview-$review->id";
        }
        return $link;
    }
    function get_options() {
        $home_domain = @parse_url(get_home_url());
        $home_domain = $home_domain['scheme'] . "://" . $home_domain['host'] . '/';
        $default_options = array(
            'ask_custom' => array(),
            'ask_fields' => array('fname' => 1, 'femail' => 1, 'fwebsite' => 0, 'ftitle' => 0),
            'dbversion' => 0,
            'field_custom' => array(),
            'form_location' => 0,
            'goto_leave_text' => __('Click here to submit your review.', 'crucible'),
            'goto_show_button' => 1,
            'leave_text' => __('Submit your review', 'crucible'),
            'require_custom' => array(),
            'require_fields' => array('fname' => 1, 'femail' => 1, 'fwebsite' => 0, 'ftitle' => 0),
            'show_custom' => array(),
            'show_fields' => array('fname' => 1, 'femail' => 0, 'fwebsite' => 0, 'ftitle' => 1),
			'submit_button_text' => __('Submit your review', 'crucible'),
            'title_tag' => 'h2'
        );
         $this->options = get_option('smar_options', $default_options);
        /* magically easy migrations to newer versions */
        $has_new = false;
        foreach ($default_options as $col => $def_val) {
            if (!isset($this->options[$col])) {
                $this->options[$col] = $def_val;
                $has_new = true;
            }
            if (is_array($def_val)) {
                foreach ($def_val as $acol => $aval) {
                    if (!isset($this->options[$col][$acol])) {
                        $this->options[$col][$acol] = $aval;
                        $has_new = true;
                    }
                }
            }
        }
        if ($has_new) {
            update_option('smar_options', $this->options);
        }
    }
    function make_p_obj() {
        $this->p = new stdClass();
        foreach ($_GET as $c => $val) {
            if (is_array($val)) {
                $this->p->$c = $val;
            } else {
                $this->p->$c = trim(stripslashes($val));
            }
        }

        foreach ($_POST as $c => $val) {
            if (is_array($val)) {
                $this->p->$c = $val;
            } else {
                $this->p->$c = trim(stripslashes($val));
            }
        }
    }
    function check_migrate() {
        global $wpdb;
        $migrated = false;
        /* remove me after official release */
        $current_dbversion = intval(str_replace('.', '', $this->options['dbversion']));
        $plugin_db_version = intval(str_replace('.', '', $this->version));
        if ($current_dbversion == $plugin_db_version) {
            return false;
        }
		
		$this->createUpdateReviewtable(); /* creates table */
		
        /* initial installation */
        if ($current_dbversion == 0) {
           $this->options['dbversion'] = $plugin_db_version;
            $current_dbversion = $plugin_db_version;
            update_option('smar_options', $this->options);
            return false;
        }
        
        /* Push dbversion to current version */
        if ($current_dbversion != $plugin_db_version || $migrated == true) {
            $this->options['dbversion'] = $plugin_db_version;
            $current_dbversion = $plugin_db_version;
            update_option('smar_options', $this->options);

			$this->force_update_cache(); // update any caches
            return true;
        }
        return false;
    }

	function template_redirect() {
		/* do this in template_redirect so we can try to redirect cleanly */
        global $post;
        if (!isset($post) || !isset($post->ID)) {
            $post = new stdClass();
            $post->ID = 0;
        }
        if (isset($_COOKIE['smar_status_msg'])) {
            $this->status_msg = $_COOKIE['smar_status_msg'];
            if ( !headers_sent() ) {
                setcookie('smar_status_msg', '', time() - 3600); /* delete the cookie */
                unset($_COOKIE['smar_status_msg']);
            }
        }
        $GET_P = "submitsmar_$post->ID";
        if ($post->ID > 0 && isset($this->p->$GET_P) && $this->p->$GET_P == $this->options['submit_button_text'])
        {
            $msg = $this->add_review($post->ID);
            $has_error = $msg[0];
            $status_msg = $msg[1];
            $url = get_permalink($post->ID);
            $cookie = array('smar_status_msg' => $status_msg);
            $this->smar_redirect($url, $cookie);// @new this is prob maybe
        }
	}
    function rand_string($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = '';

        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }
	
	/**
	* fills the value for got_aggregate()
	*/
    function get_aggregate_reviews() {
        if ($this->got_aggregate !== false) {
            return $this->got_aggregate;
        }
        global $wpdb;
        $pageID = get_option('smartestthemes_reviews_page_id');// @test with new query below, on line 242
        $row = $wpdb->get_results("SELECT COUNT(*) AS `total`,AVG(review_rating) AS `aggregate_rating`,MAX(review_rating) AS `max_rating` FROM `$this->dbtable` WHERE `status`=1");
        /* make sure we have at least one review before continuing below */
        if ($wpdb->num_rows == 0 || $row[0]->total == 0) {
            $this->got_aggregate = array("aggregate" => 0, "max" => 0, "total" => 0, "text" => __('Reviews for my site', 'crucible'));
            return false;
        }
        $aggregate_rating = $row[0]->aggregate_rating;
        $max_rating = $row[0]->max_rating;
        $total_reviews = $row[0]->total;
        $row = $wpdb->get_results("SELECT `review_text` FROM `$this->dbtable` WHERE `page_id`=$pageID AND `status`=1 ORDER BY `date_time` DESC");
		$sample_text = ! empty( $row[0]->review_text ) ? substr($row[0]->review_text, 0, 180) : '';
        $this->got_aggregate = array("aggregate" => $aggregate_rating, "max" => $max_rating, "total" => $total_reviews, "text" => $sample_text);
        return true;
    }
	
    function get_reviews($startpage, $perpage, $status) {
        global $wpdb;
        $startpage = $startpage - 1; // mysql starts at 0 instead of 1, so reduce them all by 1
        if ($startpage < 0) { $startpage = 0; }
		$limit = 'LIMIT ' . $startpage * $perpage . ',' . $perpage;
		
        if ($status == -1) {
            $qry_status = '1=1';
        } else {
            $qry_status = "`status`=$status";
        }

        $reviews = $wpdb->get_results("SELECT 
            `id`,
            `date_time`,
            `reviewer_name`,
            `reviewer_email`,
            `review_title`,
            `review_text`,
            `review_response`,
            `review_rating`,
            `reviewer_url`,
            `reviewer_ip`,
            `status`,
            `page_id`,
            `custom_fields`
            FROM `$this->dbtable` WHERE $qry_status ORDER BY `date_time` DESC $limit
            ");
        $total_reviews = $wpdb->get_results("SELECT COUNT(*) AS `total` FROM `$this->dbtable` WHERE $qry_status");

        $total_reviews = $total_reviews[0]->total;

        return array($reviews, $total_reviews);
    }
	
	
	
	/**
	* Returns the HTML string for the business schema, address, and phone with microdata for the aggregate rating.
	* @param, string, the location it is called from, accepts 'reviews' or 'footer' @test
	*/
	public function get_business_schema( $location ) {
	
		global $smartestthemes_options;
		$schema = empty($smartestthemes_options['st_business_itemtype']) ? 'LocalBusiness' : $smartestthemes_options['st_business_itemtype'];
		$bn = empty($smartestthemes_options['st_business_name']) ? get_bloginfo('name') : stripslashes_deep(esc_attr($smartestthemes_options['st_business_name']));
		$phone = empty($smartestthemes_options['st_phone_number']) ? '' : $smartestthemes_options['st_phone_number'];
		$wrapper_class = ('reviews' == $location) ? 'st-reviews-business-schema' : 'st-aggregate-business-schema';
		$bn_class = ('reviews' == $location) ? 'st-reviews-business-name' : 'st-agg-rating-bn';
		$phone_class = ('reviews' == $location) ? 'st-reviews-business-phone' : 'st-agg-rating-phone';
		$closer = ('reviews' == $location) ? '</span><hr />' : '</span>';
		$out = '';
		
		if ('reviews' == $location) {
			$out .= '<div class="reviews-list" itemprop="itemReviewed" itemscope itemtype="http://schema.org/'. $schema .'"><span class="' . $wrapper_class. '">';
		} else {
			$out .= '<span class="' . $wrapper_class. '" itemprop="itemReviewed" itemscope itemtype="http://schema.org/'. $schema .'">';
		}
		
		$out .= '<a href="' . site_url('/') . '"><span itemprop="name" class="' . $bn_class. '">' . $bn . '</span></a><br />';
				
		if ( $phone) {
			$out .= '<span itemprop="telephone" class="' . $phone_class. '">' . $phone . '</span><br />';
		}

		$out .= crucible_postal_address() . $closer;
		
		return $out;
	}

	/**
	* Gathers the aggregate data.
	* @param string, the location it is called from, accepts 'reviews-footer', or 'footer'
	* @return string, the HTML for just the aggregate rating with schema.org microdata
	*/
	function get_the_aggregate_rating( $location ) {// @test pass a param through here for $location below
		
		$this->get_aggregate_reviews();// fills the values for got_aggregate
		$average_score = number_format($this->got_aggregate["aggregate"], 1);
		
		$out = '<br /><span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" id="hreview-smar-aggregate">' . __('Average rating:', 'crucible'). ' <span itemprop="ratingValue">' . $average_score . '</span> ' . __('out of', 'crucible'). ' <span itemprop="bestRating">5 </span> '. __('based on', 'crucible').' <span itemprop="ratingCount">' . $this->got_aggregate['total'] . ' </span>' . _n( 'review', 'reviews.', $this->got_aggregate['total'], 'crucible' );

		if ( 'reviews-footer' != $location ) {
			$out .= $this->get_business_schema( $location );
		}

		$out .= '</span>';
		
		// @test the $this->get_business_schema( $location ) above.
	
		return $out;
	}
	
	/**
	* Returns the HTML string for the entire aggregate rating block.
	*/
	function aggregate_footer_output() {
		$out = '<div class="st-reviews-aggregate">';// @test CSS id was smar_respond_1, 
		$out .= $this->get_the_aggregate_rating( 'footer' );// @test param
		$out .= '</div>';
		return $out;
	}
	
	/* @todo may will use a template tag inside smar-home.php instead of this content filter for more efficiency.
	* Filter the content to conditionally attach the Aggregate footer to the home page
	*/
    function homepage_aggregate_footer($content) {
		/* only if is front page & if home page is static */
		if ( is_front_page() && (get_option('show_on_front') == 'page')	) {
			return $content . $this->aggregate_footer_output();
		}
		return $content;
    }
	
	/* @test the shortcode
	* Shortcode for the Aggregate Rating
	*/
    function aggregate_footer_func() {
		return $this->aggregate_footer_output();
    }

	function iso8601($time=false) {
		if ($time === false)
			$time = time();
		$date = date('Y-m-d\TH:i:sO', $time);
		return (substr($date, 0, strlen($date) - 2) . ':' . substr($date, -2));
	}

	// @test removed 2nd param, $reviews_per_page
    function pagination($total_results) {
        global $post;

		$per_page = get_option('st_reviews_per_page');// @test if I should instead do global .
		if ( empty($per_page) || ( $per_page < 1 ) || ! is_numeric($per_page) ) {
			$per_page = 10;
		}

		
        $out = '';
        $uri = false;
        $pretty = false;

        $range = 2;
        $showitems = ($range * 2) + 1;

        $paged = $this->page;
        if ($paged == 0) { $paged = 1; }
        
        if (!isset($this->p->review_status)) {
			$this->p->review_status = 0;
		}

        $pages = ceil($total_results / $per_page);// @test

        if ($pages > 1) {
            if (is_admin()) {
                $url = '?page=smar_view_reviews&amp;review_status=' . $this->p->review_status . '&amp;';
            } else {
                $uri = trailingslashit(get_permalink($post->ID));
                if (strpos($uri, '?') === false) {
                    $url = $uri . '?';
                    $pretty = true;
                } /* page is using pretty permalinks */ else {
                    $url = $uri . '&amp;';
                    $pretty = false;
                } /* page is using get variables for pageid */
            }

            $out .= '<div id="smar_pagination"><div id="smar_pagination_page">'. __('Page: ', 'crucible'). '</div>';

            if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                if ($uri && $pretty) {
                    $url2 = $uri;
                } /* not in admin AND using pretty permalinks */ else {
                    $url2 = $url;
                }
                $out .= '<a href="' . $url2 . '">&laquo;</a>';
            }

            if ($paged > 1 && $showitems < $pages) {
                $out .= '<a href="' . $url . 'smarp=' . ($paged - 1) . '">&lsaquo;</a>';
            }

            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $paged) {
                    $out .= '<span class="smar_current">' . $paged . '</span>';
                } else if (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems) {
                    if ($i == 1) {
                        if ($uri && $pretty) {
                            $url2 = $uri;
                        } /* not in admin AND using pretty permalinks */ else {
                            $url2 = $url;
                        }
                        $out .= '<a href="' . $url2 . '" class="smar_inactive">' . $i . '</a>';
                    } else {
                        $out .= '<a href="' . $url . 'smarp=' . $i . '" class="smar_inactive">' . $i . '</a>';
                    }
                }
            }

            if ($paged < $pages && $showitems < $pages) {
                $out .= '<a href="' . $url . 'smarp=' . ($paged + 1) . '">&rsaquo;</a>';
            }
            if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                $out .= '<a href="' . $url . 'smarp=' . $pages . '">&raquo;</a>';
            }
            $out .= '</div>';
            $out .= '<div class="smar_clear smar_pb5"></div>';

            return $out;
        }
    }
	
	/** // @test removed 1st param: $perpage, which is reviews per page
	* The HTML for the entire Reviews list
	*/
	function output_reviews_show($hide_custom = 0, $hide_response = 0, $snippet_length = 0, $show_morelink = '') {
	
		global $smartestthemes_options;
		$add_reviews = empty($smartestthemes_options['st_add_reviews']) ? '' : $smartestthemes_options['st_add_reviews'];
	
		// @test 
		$per_page = empty($smartestthemes_options['st_reviews_per_page']) ? '10' : $smartestthemes_options['st_add_reviews'];
		if ( ( $per_page < 1 ) || ! is_numeric($per_page) ) {
			$per_page = 10;
		}
		
        $arr_Reviews = $this->get_reviews($this->page, $per_page, 1);
        $reviews = $arr_Reviews[0];
        $total_reviews = intval($arr_Reviews[1]);
        $reviews_content = '';
        $showtitle = '';
        $title_tag = $this->options['title_tag'];
		
		
	
		
		/* @new remove to test if this is  multisite bug fix for not showing status_msg on when review is submitted on  multisite.
				 trying to access a page that does not exist -- send to main page 
				if ( isset($this->p->smarp) && $this->p->smarp != 1 && count($reviews) == 0 ) {
					$url = get_permalink(get_option('smartestthemes_reviews_page_id'));
					$this->smar_redirect($url);
				}
		*/        

		if (count($reviews) == 0) {
			$reviews_content .= '<p>'. __('There are no reviews yet. Be the first to leave yours!', 'crucible').'</p>';
		} elseif ($add_reviews != 'true') {
			$reviews_content .= '<p>'.__('Reviews are not available.', 'crucible').'</p>';
		} else {

			$reviews_content .= $this->get_business_schema( 'reviews' );// @test

			foreach ($reviews as $review) {
                
                if ($snippet_length > 0)
                {
                    $review->review_text = wp_trim_words( $review->review_text, $snippet_length, '<a href="'. $this->get_jumplink_for_review($review,1) .'"> ...Read More</a>' ); // @test link
                }
                
                $hide_name = '';
                if ($this->options['show_fields']['fname'] == 0) {
                    $review->reviewer_name = __('Anonymous', 'crucible');
                    $hide_name = 'smar_hide';
                }
                if ($review->reviewer_name == '') {
                    $review->reviewer_name = __('Anonymous', 'crucible');
                }

                if ($this->options['show_fields']['fwebsite'] == 1 && $review->reviewer_url != '') {
                    $review->review_text .= '<br /><small><a href="' . $review->reviewer_url . '">' . $review->reviewer_url . '</a></small>';
                }
                if ($this->options['show_fields']['femail'] == 1 && $review->reviewer_email != '') {
                    $review->review_text .= '<br /><small>' . $review->reviewer_email . '</small>';
                }
                if ($this->options['show_fields']['ftitle'] == 1 && $review->review_title != '') {
                    $showtitle = true;
                }
                
                if ($show_morelink != '') {// @test what this links to??? prob need to use in conjunction with the trim text parameter. 
                    $review->review_text .= " <a href='".$this->get_jumplink_for_review($review,1)."'>$show_morelink</a>";
                }
                
                $review->review_text = nl2br($review->review_text);
                $review_response = '';
                
                if ($hide_response == 0)// @todo this is good, document this!
                {
                    if (strlen($review->review_response) > 0) {
                        $review_response = '<p class="response"><strong>'.__('Response:', 'crucible').'</strong> ' . nl2br($review->review_response) . '</p>';
                    }
                }

                $custom_shown = '';
                if ($hide_custom == 0)
                {
                    $custom_fields_unserialized = @unserialize($review->custom_fields);
                    if (!is_array($custom_fields_unserialized)) {
                        $custom_fields_unserialized = array();
                    }
					
                    foreach ($this->options['field_custom'] as $i => $val) {  
                        if ( isset($custom_fields_unserialized[$val]) ) {
                            $show = $this->options['show_custom'][$i];							
                            if ($show == 1 && $custom_fields_unserialized[$val] != '') {
                                $custom_shown .= "<div class='smar_fl'>" . $val . ': ' . $custom_fields_unserialized[$val] . '&nbsp;&bull;&nbsp;</div>';
                            }
                        }
                    }//foreach ($this->options['field_custom
                    $custom_shown = preg_replace("%&bull;&nbsp;</div>$%si","</div><div class='smar_clear'></div>",$custom_shown);
                }// if 0 hide
				
				// @todo replace the iso8601 func
				
				$name_block = '' .'<div class="smar_fl smar_rname clear">' .'<abbr title="' . $this->iso8601(strtotime($review->date_time)) . '" itemprop="dateCreated">' . date("M d, Y", strtotime($review->date_time)) . '</abbr>&nbsp;' .'<span class="' . $hide_name . '">'. __('by', 'crucible').'</span>&nbsp;' . '<span class="isa_vcard" id="hreview-smar-reviewer-' . $review->id . '">' . '<span class="' . $hide_name . '" itemprop="author">' . $review->reviewer_name . '</span>' . '</span>' . '<div class="smar_clear"></div>' .
 $custom_shown . '</div>';
 
				$reviews_content .= '<div itemprop="review" itemscope itemtype="http://schema.org/Review" id="hreview-' . $review->id . '">';
			
				if ( $showtitle ) {
					$reviews_content .= '<' . $title_tag . ' itemprop="description" class="summary">' . $review->review_title . '</' . $title_tag . '>';
				}
			
				$reviews_content .= '<div class="smar_fl smar_sc"><div class="smar_rating">' . $this->output_rating($review->review_rating, false) . '</div></div>' . $name_block . '<div class="smar_clear smar_spacing1"></div><blockquote itemprop="reviewBody" class="description"><p>' . $review->review_text . ' '.__('Rating:', 'crucible').' <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><span itemprop="ratingValue">'.$review->review_rating.'</span></span>  '.__('out of 5.', 'crucible').'</p></blockquote>' . $review_response . '</div><hr />';

			}//  foreach ($reviews as $review)
			$reviews_content .= $this->get_the_aggregate_rating('reviews-footer') . '</div><!-- .reviews-list -->';
			// @test param 'reviews-footer' above
			
			 
			
			
		}//if else if (count($reviews
		return array($reviews_content, $total_reviews);
	}
	
	/**
	 * Create the Reviews page
	 * @uses smartestthemes_insert_post()
	 */
	public function create_reviews_page() {
		if(get_option('st_add_reviews') == 'true') {
			smartestthemes_insert_post('page', esc_sql( _x('reviews', 'page_slug', 'crucible') ), 'smartestthemes_reviews_page_id', __('Reviews', 'crucible'), '[smartest_reviews]' );
		}
	}

    public function output_rating($rating, $enable_hover) {
        $out = '';
        $rating_width = 20 * $rating; /* 20% for each star if having 5 stars */
        $out .= '<div class="sp_rating">';
        if ($enable_hover) {
            $out .= '<div class="status"><div class="score"><a class="score1">1</a><a class="score2">2</a><a class="score3">3</a><a class="score4">4</a><a class="score5">5</a></div></div>';
        }

        $out .= '<div class="base"><div class="average" style="width:' . $rating_width . '%"></div></div>';
        $out .= '</div>';

        return $out;
    }

    function show_reviews_form() {
        global $post, $current_user;
        $fields = '';
        $out = '';
        $req_js = "<script type='text/javascript'>";
        if ( isset($_COOKIE['smar_status_msg']) ) {
            $this->status_msg = $_COOKIE['smar_status_msg'];
        }
        if ($this->status_msg != '') {
            $req_js .= "smar_del_cookie('smar_status_msg');";
        }
        /* a silly and crazy but effective antispam measure.. bots wont have a clue */
        $rand_prefixes = array();
        for ($i = 0; $i < 15; $i++) {
            $rand_prefixes[] = $this->rand_string(mt_rand(1, 8));
        }
        if (!isset($this->p->fname)) { $this->p->fname = ''; }
        if (!isset($this->p->femail)) { $this->p->femail = ''; }
        if (!isset($this->p->fwebsite)) { $this->p->fwebsite = ''; }
        if (!isset($this->p->ftitle)) { $this->p->ftitle = ''; }
        if (!isset($this->p->ftext)) { $this->p->ftext = ''; }

        if ($this->options['ask_fields']['fname'] == 1) {
            if ($this->options['require_fields']['fname'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[0] . '-fname" class="comment-field">'. __('Name:', 'crucible').' ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[0] . '-fname" name="' . $rand_prefixes[0] . '-fname" value="' . $this->p->fname . '" /></td></tr>';
        }
        if ($this->options['ask_fields']['femail'] == 1) {
            if ($this->options['require_fields']['femail'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[1] . '-femail" class="comment-field">'. __('Email:', 'crucible').' ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[1] . '-femail" name="' . $rand_prefixes[1] . '-femail" value="' . $this->p->femail . '" /></td></tr>';
        }
        if ($this->options['ask_fields']['fwebsite'] == 1) {
            if ($this->options['require_fields']['fwebsite'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[2] . '-fwebsite" class="comment-field">'. __('Website:', 'crucible').' ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[2] . '-fwebsite" name="' . $rand_prefixes[2] . '-fwebsite" value="' . $this->p->fwebsite . '" /></td></tr>';
        }
        if ($this->options['ask_fields']['ftitle'] == 1) {
            if ($this->options['require_fields']['ftitle'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[3] . '-ftitle" class="comment-field">'. __('Review Title:', 'crucible').' ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[3] . '-ftitle" name="' . $rand_prefixes[3] . '-ftitle" maxlength="150" value="' . $this->p->ftitle . '" /></td></tr>';
        }

        $custom_fields = array(); /* used for insert as well */
        $custom_count = count($this->options['field_custom']); /* used for insert as well */
        for ($i = 0; $i < $custom_count; $i++) {
            $custom_fields[$i] = $this->options['field_custom'][$i];
        }

        foreach ($this->options['ask_custom'] as $i => $val) {
            if ( isset($this->options['ask_custom'][$i]) ) {
                if ($val == 1) {
                    if ($this->options['require_custom'][$i] == 1) {
                        $req = '*';
                    } else {
                        $req = '';
                    }

                    $custom_i = "custom_$i";
                    if (!isset($this->p->$custom_i)) { $this->p->$custom_i = ''; }
                    $fields .= '<tr><td><label for="custom_' . $i . '" class="comment-field">' . $custom_fields[$i] . ': ' . $req . '</label></td><td><input class="text-input" type="text" id="custom_' . $i . '" name="custom_' . $i . '" maxlength="150" value="' . $this->p->$custom_i . '" /></td></tr>';
                }
            } 
        }

        $some_required = '';
        
        foreach ($this->options['require_fields'] as $col => $val) {
            if ($val == 1) {
                $col = str_replace("'","\'",$col);
                $req_js .= "smar_req.push('$col');";
                $some_required = '<small>* '. __('Required Field', 'crucible').'</small>';
            }
        }

        foreach ($this->options['require_custom'] as $i => $val) {
            if ($val == 1) {
                $req_js .= "smar_req.push('custom_$i');";
                $some_required = '<small>* '. __('Required Field', 'crucible').'</small>';
            }
        }
        
        $req_js .= "</script>\n";
        
        if ($this->options['goto_show_button'] == 1) {
            $button_html = '<div class="smar_status_msg">' . $this->status_msg . '</div>'; /* show errors or thank you message here */
            $button_html .= '<p><a id="smar_button_1" href="javascript:void(0);">' . $this->options['goto_leave_text'] . '</a></p>';
            $out .= $button_html;
        }

        /* different output variables make it easier to debug this section */
        $out .= '<div id="smar_respond_2">' . $req_js . '
                    <form class="smarcform" id="smar_commentform" method="post" action="javascript:void(0);">
                        <div id="smar_div_2">
                            <input type="hidden" id="frating" name="frating" />
                            <table id="smar_table_2">
                                <tbody>
                                    <tr><td colspan="2"><div id="smar_postcomment">' . $this->options["leave_text"] . '</div></td></tr>
                                    ' . $fields;

        $out2 = '   
            <tr>
                <td><label class="comment-field">'. __('Rating:', 'crucible').'</label></td>
                <td><div class="smar_rating">' . $this->output_rating(0, true) . '</div></td>
            </tr>';

        $out3 = '
                            <tr><td colspan="2"><label for="' . $rand_prefixes[5] . '-ftext" class="comment-field">'. __('Review:', 'crucible').'</label></td></tr>
                            <tr><td colspan="2"><textarea id="' . $rand_prefixes[5] . '-ftext" name="' . $rand_prefixes[5] . '-ftext" rows="8" cols="50">' . $this->p->ftext . '</textarea></td></tr>
                            <tr>
                                <td colspan="2" id="smar_check_confirm">
                                    ' . $some_required . '
                                    <div class="smar_clear"></div>    
                                    <input type="checkbox" name="' . $rand_prefixes[6] . '-fconfirm1" id="fconfirm1" value="1" />
                                    <div class="smar_fl"><input type="checkbox" name="' . $rand_prefixes[7] . '-fconfirm2" id="fconfirm2" value="1" /></div><div class="smar_fl smar_checklabel"><label for="fconfirm2">'. __('Check this box to confirm you are human.', 'crucible').'</label></div>
                                    <div class="smar_clear"></div>
                                    <input type="checkbox" name="' . $rand_prefixes[8] . '-fconfirm3" id="fconfirm3" value="1" />
                                </td>
                            </tr>
                            <tr><td colspan="2"><input id="smar_submit_btn" name="submitsmar_' . $post->ID . '" type="submit" value="' . $this->options['submit_button_text'] . '" /></td></tr>
                        </tbody>
                    </table>
                </div>
            </form>';

        $out4 = '<hr /></div>';
        $out4 .= '<div class="smar_clear smar_pb5"></div>';

        return $out . $out2 . $out3 . $out4;
    }

    function add_review($pageID) {
        global $wpdb;

        /* begin - some antispam magic */
        $this->newp = new stdClass();

        foreach ($this->p as $col => $val) {
            $pos = strpos($col, '-');
            if ($pos !== false) {
                $col = substr($col, $pos + 1); /* off by one */
            }
            $this->newp->$col = $val;
        }

        $this->p = $this->newp;
        unset($this->newp);
        /* end - some antispam magic */

        /* some sanitation */
        $date_time = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if (!isset($this->p->fname)) { $this->p->fname = ''; }
        if (!isset($this->p->femail)) { $this->p->femail = ''; }
        if (!isset($this->p->fwebsite)) { $this->p->fwebsite = ''; }
        if (!isset($this->p->ftitle)) { $this->p->ftitle = ''; }
        if (!isset($this->p->ftext)) { $this->p->ftext = ''; }
        if (!isset($this->p->femail)) { $this->p->femail = ''; }
        if (!isset($this->p->fwebsite)) { $this->p->fwebsite = ''; }
        if (!isset($this->p->frating)) { $this->p->frating = 0; } /* default to 0 */
        if (!isset($this->p->fconfirm1)) { $this->p->fconfirm1 = 0; } /* default to 0 */
        if (!isset($this->p->fconfirm2)) { $this->p->fconfirm2 = 0; } /* default to 0 */
        if (!isset($this->p->fconfirm3)) { $this->p->fconfirm3 = 0; } /* default to 0 */
        
        $this->p->fname = trim(strip_tags($this->p->fname));
        $this->p->femail = trim(strip_tags($this->p->femail));
        $this->p->ftitle = trim(strip_tags($this->p->ftitle));
        $this->p->ftext = trim(strip_tags($this->p->ftext));
        $this->p->frating = intval($this->p->frating);

        /* begin - server-side validation */
        $errors = '';

        foreach ($this->options['require_fields'] as $col => $val) {
            if ($val == 1) {
                if (!isset($this->p->$col) || $this->p->$col == '') {
                    $nice_name = ucfirst(substr($col, 1));
                    $errors .= __('You must include your', 'crucible').' ' . $nice_name . '.<br />';
                }
            }
        }

        $custom_fields = array(); /* used for insert as well */
        $custom_count = count($this->options['field_custom']); /* used for insert as well */
        for ($i = 0; $i < $custom_count; $i++) {
            $custom_fields[$i] = $this->options['field_custom'][$i];
        }

        foreach ($this->options['require_custom'] as $i => $val) {
            if ($val == 1) {
                $custom_i = "custom_$i";
                if (!isset($this->p->$custom_i) || $this->p->$custom_i == '') {
                    $nice_name = $custom_fields[$i];
                    $errors .= __('You must include your', 'crucible').' ' . $nice_name . '.<br />';
                }
            }
        }
        
        /* only do regex matching if not blank */
        if ($this->p->femail != '' && $this->options['ask_fields']['femail'] == 1) {
            if (!preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/', $this->p->femail)) {
                $errors .= __('The email address provided is not valid.', 'crucible').'<br />';
            }
        }

        /* only do regex matching if not blank */
        if ($this->p->fwebsite != '' && $this->options['ask_fields']['fwebsite'] == 1) {
            if (!preg_match('/^\S+:\/\/\S+\.\S+.+$/', $this->p->fwebsite)) {
                $errors .= __('The website provided is not valid. Be sure to include http://', 'crucible').'<br />';
            }
        }

        if (intval($this->p->fconfirm1) == 1 || intval($this->p->fconfirm3) == 1) {
            $errors .= __('You have triggered our anti-spam system. Please try again. Code 001.', 'crucible').'<br />';
        }

        if (intval($this->p->fconfirm2) != 1) {
            $errors .= __('You have triggered our anti-spam system. Please try again. Code 002', 'crucible').'<br />';
        }

        if ($this->p->frating < 1 || $this->p->frating > 5) {
            $errors .= __('You have triggered our anti-spam system. Please try again. Code 003', 'crucible').'<br />';
        }

       if (strlen(trim($this->p->ftext)) < 5) {
            $errors .= __('You must include a review. Please make reviews at least 5 letters.', 'crucible').'<br />';
        }

        /* returns true for errors */
        if ($errors) {
            return array(true, "<div>$errors</div>");
        }
        /* end - server-side validation */

        $custom_insert = array();

		 $this->options['ask_custom'] = array(0, 1, 2, 3, 4, 5);
		
        for ($i = 0; $i < $custom_count; $i++) {		
            if ($this->options['ask_custom'][$i] == 1) {
                $name = $custom_fields[$i];
                $custom_i = "custom_$i";				
                if ( isset($this->p->$custom_i) ) {
                    $custom_insert[$name] = ucfirst($this->p->$custom_i);
                }
            }
        }
        $custom_insert = serialize($custom_insert);
        $query = $wpdb->prepare("INSERT INTO `$this->dbtable` 
                (`date_time`, `reviewer_name`, `reviewer_email`, `reviewer_ip`, `review_title`, `review_text`, `status`, `review_rating`, `reviewer_url`, `custom_fields`, `page_id`) 
                VALUES (%s, %s, %s, %s, %s, %s, %d, %d, %s, %s, %d)", $date_time, $this->p->fname, $this->p->femail, $ip, $this->p->ftitle, $this->p->ftext, 0, $this->p->frating, $this->p->fwebsite, $custom_insert, $pageID);

        $wpdb->query($query);
		$smartestthemes_options = get_option('smartestthemes_options');
		$bn = stripslashes_deep($smartestthemes_options['st_business_name']);if(!$bn) {$bn = get_bloginfo('name'); }
        $admin_linkpre = get_admin_url().'admin.php?page=smar_view_reviews';
        $admin_link = sprintf(__('Link to admin approval page: %s', 'crucible'), $admin_linkpre);
		$ac = sprintf(__('A new review has been posted on %1$s\'s website.','crucible'),$bn) . "\n\n" .
	__('You will need to login to the admin area and approve this review before it will appear on your site.','crucible') . "\n\n" .$admin_link;

        @wp_mail(get_bloginfo('admin_email'), $bn.': '. sprintf(__('New Review Posted on %1$s', 'crucible'), 
								date('m/d/Y h:i e') ), $ac );

        /* returns false for no error */
        return array(false, '<div>'.__('Thank you for your comments. All submissions are moderated and if approved, yours will appear soon.', 'crucible').'</div>');
    }
    function smar_redirect($url, $cookie = array()) {
        $headers_sent = headers_sent();
        if ($headers_sent == true) {
            /* use JS redirect and add cookie before redirect */
            /* we do not html comment script blocks here - to prevent any issues with other plugins adding content to newlines, etc */
            $out = '<html><head><title>'.__('Redirecting', 'crucible').'...</title></head><body><div style="clear:both;text-align:center;padding:10px;">' .
                    __('Processing... Please wait...', 'crucible') .
                    '<script type="text/javascript">';
            foreach ($cookie as $col => $val) {
                $val = preg_replace("/\r?\n/", "\\n", addslashes($val));
                $out .= "document.cookie=\"$col=$val\";";
            }
            $out .= "window.location='$url';";
            $out .= "</script>";
            $out .= "</div></body></html>";
            echo $out;
        } else {
            foreach ($cookie as $col => $val) {
                setcookie($col, $val); /* add cookie via headers */
            }
		if (ob_get_length()) ob_end_clean();
            wp_redirect($url);
        }
        
        exit();
    }

    public function init() { /* used for admin_init also */
        $this->make_p_obj(); /* make P variables object */
        $this->get_options();
        $this->check_migrate(); /* call on every instance to see if we have upgraded in any way */
		
		
        if ( !isset($this->p->smarp) ) {
			$this->p->smarp = 1;
		}
        $this->page = intval($this->p->smarp);
        if ($this->page < 1) {
			$this->page = 1;
		}
		
    }
	
	/**
	* The reviews page shortcode
	*/
	public function reviews_shortcode( $atts ) {
	
		// @test remove global $post;// @test do i need this

		$reviews_content = '<div id="smar_respond_1">';
       
		if ($this->options['form_location'] == 0) {
			$reviews_content .= $this->show_reviews_form();
		}

		// @todo all these params are available for use:
		// consider use $atts and letting them add atts to shortcode, or else just remove these extra functionality and wasted codespace.
		
		// function output_reviews_show($perpage, $hide_custom = 0, $hide_response = 0, $snippet_length = 0, $show_morelink = '').
		
		
		$ret_Arr = $this->output_reviews_show();// @test with 2 params
			// @test removed reviews per page param!!
		
        $reviews_content .= $ret_Arr[0];
        $total_reviews = $ret_Arr[1];
        
		$reviews_content .= $this->pagination($total_reviews);

        if ($this->options['form_location'] == 1) {
            $reviews_content .= $this->show_reviews_form();
        }
        $reviews_content .= '</div>';
        
		// @test may not need this?? 	$reviews_content = preg_replace('/\n\r|\r\n|\n|\r|\t/', '', $reviews_content); /* minify to prevent automatic line breaks, not removing double spaces */

        return $reviews_content;
	
	
	}// end reviews_shortcode
	
	function smartestreviews_scripts() {
		if( get_option('st_add_reviews') == 'true'  ) {
			wp_register_style('smartest-reviews', $this->getpluginurl() . 'reviews.css', array(), $this->version);
			wp_register_script('smartest-reviews', $this->getpluginurl() . 'reviews.js', array('jquery'), $this->version);
			if( is_page(get_option('smartestthemes_reviews_page_id'))) {
				wp_enqueue_style('smartest-reviews');
		        wp_enqueue_script('smartest-reviews');
				$loc = array(
					'hidebutton' => __('Click here to hide form', 'crucible'),
					'email' => __('The email address provided is not valid.', 'crucible'),
					'name' => __('You must include your ', 'crucible'),
					'review' => __('You must include a review. Please make reviews at least 4 letters.', 'crucible'),
					'human' => __('You must confirm that you are human.', 'crucible'),
					'code2' => __('Code 2.', 'crucible'),
					'code3' => __('Code 3.', 'crucible'),
					'rating' => __('Please select a star rating from 1 to 5.', 'crucible'),
					'website' => __('The website provided is not valid. Be sure to include', 'crucible')
					);
				wp_localize_script( 'smartest-reviews', 'smartlocal', $loc);
			}
		}
	}
	/**
	 * widget
	 */
	public function smartest_reviews_register_widgets() {
		if( get_option('st_add_reviews') == 'true'  ) {
			register_widget('SmartestReviewsTestimonial');
		}
	}
	
	public function getpluginurl() {
		return get_template_directory_uri().'/business-framework/modules/reviews/';
	}
	
	public function admin_init() {
		$this->init();
		register_setting( 'smar_options', 'smar_options' );
	}
	
	public function admin_save_post($post_id, $post) {
		global $meta_box,$wpdb;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ( isset($this->p->post_type) && $this->p->post_type == 'page' ) {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

			if ( isset($meta_box) && isset($meta_box['fields']) && is_array($meta_box['fields']) )
			{
				foreach ($meta_box['fields'] as $field) {
					
					if ( isset($this->p->post_title) ) {
						$old = get_post_meta($post_id, $field['id'], true);
						
						if (isset($this->p->$field['id'])) {
							$new = $this->p->$field['id'];
							if ($new && $new != $old) {
								update_post_meta($post_id, $field['id'], $new);
							} elseif ($new == '' && $old) {
								delete_post_meta($post_id, $field['id'], $old);
							}
						} else {
							delete_post_meta($post_id, $field['id'], $old);
						}
					}
					
				}
			}

            return $post_id;
	}
	
	
	function createUpdateReviewTable() {
            require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
            
            $sql = "CREATE TABLE $this->dbtable (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      date_time datetime NOT NULL,
                      reviewer_name varchar(150) DEFAULT NULL,
                      reviewer_email varchar(150) DEFAULT NULL,
                      reviewer_ip varchar(15) DEFAULT NULL,
                      review_title varchar(150) DEFAULT NULL,
                      review_text text,
                      review_response text,
                      status tinyint(1) DEFAULT '0',
                      review_rating tinyint(2) DEFAULT '0',
                      reviewer_url varchar(255) NOT NULL,
                      page_id int(11) NOT NULL DEFAULT '0',
                      custom_fields text,
                      PRIMARY KEY  (id),
                      KEY status (status),
                      KEY page_id (page_id)
                      )";
            
            dbDelta($sql);
        }	
	
	public function force_update_cache() {
			return; /* @todo maybe remove testing to increase performance */
			global $wpdb;
				
			/* update all pages */
			$pages = $wpdb->get_results( "SELECT `ID` FROM $wpdb->posts AS `p`" );
			foreach ($pages as $page) {
                $post = get_post($page->ID);
				if ($post) {
					clean_post_cache($page->ID);
					wp_update_post($post);
                }
            }
    }

	public function enqueue_admin_stuff() {
		$pluginurl = $this->getpluginurl();
		if (isset($this->p->page) && ( $this->p->page == 'smar_view_reviews' || $this->p->page == 'smar_options' ) ) {
			wp_enqueue_script('smartest-reviews-admin',$pluginurl.'reviews-admin.js',array('jquery'));
			wp_enqueue_style('smartest-reviews-admin',$pluginurl.'reviews-admin.css');
		}
	}	
	
	public function update_options() {
        /* we still process and validate this internally, instead of using the Settings API */
        global $wpdb;
        $this->security();
           check_admin_referer('smar_options-options'); /* nonce check */
            $updated_options = $this->options;
            /* reset these to 0 so we can grab the settings below */
            $updated_options['ask_fields']['fname'] = 0;
            $updated_options['ask_fields']['femail'] = 0;
            $updated_options['ask_fields']['fwebsite'] = 0;
            $updated_options['ask_fields']['ftitle'] = 0;
            $updated_options['require_fields']['fname'] = 0;
            $updated_options['require_fields']['femail'] = 0;
            $updated_options['require_fields']['fwebsite'] = 0;
            $updated_options['require_fields']['ftitle'] = 0;
            $updated_options['show_fields']['fname'] = 0;
            $updated_options['show_fields']['femail'] = 0;
            $updated_options['show_fields']['fwebsite'] = 0;
            $updated_options['show_fields']['ftitle'] = 0;
            $updated_options['ask_custom'] = array();
            $updated_options['field_custom'] = array();
            $updated_options['require_custom'] = array();
            $updated_options['show_custom'] = array();
		
            /* quick update of all options needed */
            foreach ($this->p as $col => $val)
            {
                if (isset($this->options[$col]))
                {
                    switch($col)
                    {
                        case 'field_custom': /* we should always hit field_custom before ask_custom, etc */
                            foreach ($val as $i => $name) { $updated_options[$col][$i] = ucwords( strtolower( $name ) ); } /* we are so special */
                            break;
                        case 'ask_custom':
                        case 'require_custom':
                        case 'show_custom':
                            foreach ($val as $i => $v) { $updated_options[$col][$i] = 1; } /* checkbox array with ints */
                            break;
                        case 'ask_fields':
                        case 'require_fields':
                        case 'show_fields':
                            foreach ($val as $v) { $updated_options[$col]["$v"] = 1; } /* checkbox array with names */
                            break;
                        default:
                            $updated_options[$col] = $val; /* a non-array normal field */
                            break;
                    }
                }
            }
            
            /* prevent E_NOTICE warnings */
			if (!isset($this->p->goto_show_button)) { $this->p->goto_show_button = 0; }
			$updated_options['form_location'] = intval($this->p->form_location);
			$updated_options['goto_show_button'] = intval($this->p->goto_show_button);
			
			
            
			
            update_option('smar_options', $updated_options);
            $this->force_update_cache(); /* update any caches */
        
       return __('Your settings have been saved.', 'crucible');
    }
	public function show_options() {



        $goto_show_button_checked = '';
        if ($this->options['goto_show_button']) {
            $goto_show_button_checked = 'checked';
        }
        $af = array('fname' => '','femail' => '','fwebsite' => '','ftitle' => '');
        if ($this->options['ask_fields']['fname'] == 1) { $af['fname'] = 'checked'; }
        if ($this->options['ask_fields']['femail'] == 1) { $af['femail'] = 'checked'; }
        if ($this->options['ask_fields']['fwebsite'] == 1) { $af['fwebsite'] = 'checked'; }
        if ($this->options['ask_fields']['ftitle'] == 1) { $af['ftitle'] = 'checked'; }
        $rf = array('fname' => '','femail' => '','fwebsite' => '','ftitle' => '');
        if ($this->options['require_fields']['fname'] == 1) { $rf['fname'] = 'checked'; }
        if ($this->options['require_fields']['femail'] == 1) { $rf['femail'] = 'checked'; }
        if ($this->options['require_fields']['fwebsite'] == 1) { $rf['fwebsite'] = 'checked'; }
        if ($this->options['require_fields']['ftitle'] == 1) { $rf['ftitle'] = 'checked'; }
        $sf = array('fname' => '','femail' => '','fwebsite' => '','ftitle' => '');
        if ($this->options['show_fields']['fname'] == 1) { $sf['fname'] = 'checked'; }
        if ($this->options['show_fields']['femail'] == 1) { $sf['femail'] = 'checked'; }
        if ($this->options['show_fields']['fwebsite'] == 1) { $sf['fwebsite'] = 'checked'; }
        if ($this->options['show_fields']['ftitle'] == 1) { $sf['ftitle'] = 'checked'; }
        echo '
        <div class="postbox" style="width:700px;"><h3>'. __('Display Options', 'crucible') .'</h3><div id="smar_ad">
               <form method="post" action=""><div style="background:#eaf2fa;padding:6px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
                        <legend>'. __('General Settings', 'crucible').'</legend>
                    </div>
					
					
					



					

       <div style="background:#eaf2fa;padding:6px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;"><legend>'. __('Review Page Settings', 'crucible'). '</legend></div>
                    <div style="padding:10px;padding-bottom:10px;">
					

					
					
                        <br /><br />
                        <label for="form_location">'. __('Location of Review Form: ', 'crucible'). '</label>
                        <select id="form_location" name="form_location">
                            <option ';if ($this->options['form_location'] == 0) { echo "selected"; } echo ' value="0">'. __('Above Reviews', 'crucible'). '</option>
                            <option ';if ($this->options['form_location'] == 1) { echo "selected"; } echo ' value="1">'. __('Below Reviews', 'crucible'). '</option>                     </select>
                        <br /><br />
                        <label>'. __('Fields to ask for on review form: ', 'crucible'). '</label>
                        <input data-what="fname" id="ask_fname" name="ask_fields[]" type="checkbox" '.$af['fname'].' value="fname" />&nbsp;<label for="ask_fname"><small>'. __('Name', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input data-what="femail" id="ask_femail" name="ask_fields[]" type="checkbox" '.$af['femail'].' value="femail" />&nbsp;<label for="ask_femail"><small>'. __('Email', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input data-what="fwebsite" id="ask_fwebsite" name="ask_fields[]" type="checkbox" '.$af['fwebsite'].' value="fwebsite" />&nbsp;<label for="ask_fwebsite"><small>'. __('Website', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input data-what="ftitle" id="ask_ftitle" name="ask_fields[]" type="checkbox" '.$af['ftitle'].' value="ftitle" />&nbsp;<label for="ask_ftitle"><small>'. __('Review Title', 'crucible'). '</small></label>
                        <br /><br />
                        <label>'. __('Fields to require on review form: ', 'crucible'). '</label>
                        <input id="require_fname" name="require_fields[]" type="checkbox" '.$rf['fname'].' value="fname" />&nbsp;<label for="require_fname"><small>'. __('Name', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="require_femail" name="require_fields[]" type="checkbox" '.$rf['femail'].' value="femail" />&nbsp;<label for="require_femail"><small>'. __('Email', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="require_fwebsite" name="require_fields[]" type="checkbox" '.$rf['fwebsite'].' value="fwebsite" />&nbsp;<label for="require_fwebsite"><small>'. __('Website', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="require_ftitle" name="require_fields[]" type="checkbox" '.$rf['ftitle'].' value="ftitle" />&nbsp;<label for="require_ftitle"><small>'. __('Review Title', 'crucible'). '</small></label>
                        <br /><br />
                        <label>'. __('Fields to show on each approved review: ', 'crucible'). '</label>
                        <input id="show_fname" name="show_fields[]" type="checkbox" '.$sf['fname'].' value="fname" />&nbsp;<label for="show_fname"><small>'. __('Name', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="show_femail" name="show_fields[]" type="checkbox" '.$sf['femail'].' value="femail" />&nbsp;<label for="show_femail"><small>'. __('Email', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="show_fwebsite" name="show_fields[]" type="checkbox" '.$sf['fwebsite'].' value="fwebsite" />&nbsp;<label for="show_fwebsite"><small>'. __('Website', 'crucible'). '</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="show_ftitle" name="show_fields[]" type="checkbox" '.$sf['ftitle'].' value="ftitle" />&nbsp;<label for="show_ftitle"><small>'. __('Review Title', 'crucible'). '</small></label>
                        <br />
                        <small>'. __('It is usually NOT a good idea to show email addresses publicly.', 'crucible'). '</small>
                        <br /><br />
                        <label>'. __('Custom fields on review form: ', 'crucible'). '</label>(<small>'. __('You can type in the names of any additional fields you would like here.', 'crucible'). '</small>)
                        <div style="font-size:10px;padding-top:6px;">
                        ';
                        for ($i = 0; $i < 6; $i++) /* 6 custom fields */
                        {
                            if ( !isset($this->options['ask_custom'][$i]) ) { $this->options['ask_custom'][$i] = 0; }
                            if ( !isset($this->options['require_custom'][$i]) ) { $this->options['require_custom'][$i] = 0; }
                            if ( !isset($this->options['show_custom'][$i]) ) { $this->options['show_custom'][$i] = 0; }
                            
                            if ($this->options['ask_custom'][$i] == 1) { $caf = 'checked'; } else { $caf = ''; }
                            if ($this->options['require_custom'][$i] == 1) { $crf = 'checked'; } else { $crf = ''; }
                            if ($this->options['show_custom'][$i] == 1) { $csf = 'checked'; } else { $csf = ''; }
                            echo '
                            <label for="field_custom'.$i.'">'. __('Field Name: ', 'crucible'). '</label><input id="field_custom'.$i.'" name="field_custom['.$i.']" type="text" value="'.$this->options['field_custom'][$i].'" />&nbsp;&nbsp;&nbsp;
                            <input '.$caf.' class="custom_ask" data-id="'.$i.'" id="ask_custom'.$i.'" name="ask_custom['.$i.']" type="checkbox" value="1" />&nbsp;<label for="ask_custom'.$i.'">'. __('Ask', 'crucible'). '</label>&nbsp;&nbsp;&nbsp;
                            <input '.$crf.' class="custom_req" data-id="'.$i.'" id="require_custom'.$i.'" name="require_custom['.$i.']" type="checkbox" value="1" />&nbsp;<label for="require_custom'.$i.'">'. __('Require', 'crucible'). '</label>&nbsp;&nbsp;&nbsp;
                            <input '.$csf.' class="custom_show" data-id="'.$i.'" id="show_custom'.$i.'" name="show_custom['.$i.']" type="checkbox" value="1" />&nbsp;<label for="show_custom'.$i.'">'. __('Show', 'crucible'). '</label><br />
                            ';
                        }
                        echo '
                        </div>
                        <br /><br />
                        <label for="title_tag">'. __('Heading to use for Review Titles: ', 'crucible'). '</label>
                        <select id="title_tag" name="title_tag">
                            <option ';if ($this->options['title_tag'] == 'h2') { echo "selected"; } echo ' value="h2">H2</option>
                            <option ';if ($this->options['title_tag'] == 'h3') { echo "selected"; } echo ' value="h3">H3</option>
                            <option ';if ($this->options['title_tag'] == 'h4') { echo "selected"; } echo ' value="h4">H4</option>
                            <option ';if ($this->options['title_tag'] == 'h5') { echo "selected"; } echo ' value="h5">H6</option>
                            <option ';if ($this->options['title_tag'] == 'h6') { echo "selected"; } echo ' value="h6">H7</option>
                        </select>
                        <br /><br />
                        <label for="goto_show_button">'. __('Show review form: ', 'crucible'). '</label><input type="checkbox" id="goto_show_button" name="goto_show_button" value="1" '.$goto_show_button_checked.' />
                        <br />
                        <small>'. __('If this option is unchecked, there will be no visible way for visitors to submit reviews.', 'crucible'). '</small>
                        <br /><br />
                        <label for="goto_leave_text">'. __('Button text used to show review form: ', 'crucible'). '</label><input style="width:250px;" type="text" id="goto_leave_text" name="goto_leave_text" value="'.$this->options['goto_leave_text'].'" />
                        <br />
                        <small>'. __('This button will be shown above the first review.', 'crucible'). '</small>
                        <br /><br />
                        <label for="leave_text">'. __('Text to be displayed above review form: ', 'crucible'). '</label><input style="width:250px;" type="text" id="goto_leave_text" name="goto_leave_text" value="'.$this->options['goto_leave_text'].'" />
                        <br />
                        <small>'. __('This will be shown as a heading immediately above the review form.', 'crucible'). '</small>
                        <br /><br />
                        <label for="submit_button_text">'. __('Text to use for review form submit button: ', 'crucible'). '</label><input style="width:200px;" type="text" id="submit_button_text" name="submit_button_text" value="'.$this->options['submit_button_text'].'" />
                        <br />
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="'. __('Save Changes', 'crucible'). '" name="Submit"></div>
                    </div>';
                    settings_fields("smar_options");
                    echo '
                </form>
                <br />
            </div>
        </div>';
        
    }
	
	function security() {
        if (!current_user_can('manage_options'))
        {
            wp_die( __('You do not have sufficient permissions to access this page.','crucible') );
        }
    }	


function admin_options() {
        $this->security();

        $msg = '';
		
		// make sure the db is created
		global $wpdb;
		$exists = $wpdb->get_var("SHOW TABLES LIKE '$this->dbtable'");
		if ($exists != $this->dbtable) {
			$exists = $wpdb->get_var("SHOW TABLES LIKE '$this->dbtable'");
			if ($exists != $this->dbtable) {
				print "<br /><br /><br /><p class='warning'>". __('COULD NOT CREATE DATABASE TABLE, PLEASE REPORT THIS ERROR', 'crucible'). "</p>";
			}
		}
        
        if (!isset($this->p->Submit)) { $this->p->Submit = ''; }
        
        if ($this->p->Submit == __('Save Changes', 'crucible')) {
            $msg = $this->update_options();
			$this->get_options();
        }
        
        if (isset($this->p->email)) { // @todo @test what is them email? can i delete?
            $msg = $this->update_options();
			$this->get_options();
        }
        
        echo '
        <div id="smar_respond_1" class="wrap">
            <h2>'. __('Reviews - Options', 'crucible'). '</h2>';
            if ($msg) { echo '<h3 style="color:#a00;">'.$msg.'</h3>'; }
			$themeobject = wp_get_theme();
			$admin_page = $themeobject->Template;// @test
			$linkp = '<a href="'. admin_url("admin.php?page=$admin_page").'">Preferences</a>';
            echo '<div class="metabox-holder">
            <div class="postbox" style="width:700px;">
                <h3 style="cursor:default;">'. __('About Reviews', 'crucible'). '</h3>
                <div style="padding:10px; background:#ffffff;">
                    <p>'. __('Reviews allow your customers and visitors to leave reviews or testimonials of your business. Aggregate ratings data from the Reviews page will be pulled into your home page to create rich snippets for search engines on your home page and your Reviews page. Reviews are Schema.org microdata enabled.', 'crucible'). '<br /><br />'
					. sprintf(__('Activate Reviews by checking the "Add Reviews Section" in %s.', 'crucible'), $linkp).
'</p><br /> </div> </div>';
        $this->show_options();
        echo '<br /></div>';
    }
	
	function admin_view_reviews() {
        global $wpdb;
		
		$per_page = get_option('st_reviews_per_page');// @test if I should instead do global .
		if ( empty($per_page) || ( $per_page < 1 ) || ! is_numeric($per_page) ) {
			$per_page = 10;
		}
        
        if (!isset($this->p->s)) { $this->p->s = ''; }
        $this->p->s_orig = $this->p->s;
        
        if (!isset($this->p->review_status)) { $this->p->review_status = 0; }
        $this->p->review_status = intval($this->p->review_status);
        
        /* begin - actions */
        if (isset($this->p->action)) {
		
            if (isset($this->p->r)) {
                $this->p->r = intval($this->p->r);

                switch ($this->p->action) {
                    case 'deletereview':
                        $wpdb->query("DELETE FROM `$this->dbtable` WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'trashreview':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=2 WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'approvereview':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=1 WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'unapprovereview':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=0 WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'update_field':
                        
                        ob_end_clean();
                        
                        if (!is_array($this->p->json)) {
                            header('HTTP/1.1 403 Forbidden');
                            echo json_encode(array("errors" => __('Bad Request', 'crucible')));
                            exit(); 
                        }
                        
                        $show_val = '';
                        $update_col = false;
                        $update_val = false;
                        
                        foreach ($this->p->json as $col => $val) {
                            
                            switch ($col) {
                                case 'date_time':
                                    $d = date("m/d/Y g:i a",strtotime($val));
                                    if (!$d || $d == '01/01/1970 12:00 am') {
                                        header('HTTP/1.1 403 Forbidden');
                                        echo json_encode(array("errors" => __('Bad Date Format', 'crucible')));
                                        exit(); 
                                    }
                                    
                                    $show_val = $d;
                                    $d2 = date("Y-m-d H:i:s",strtotime($val));
                                    $update_col = esc_sql($col);
                                    $update_val = esc_sql($d2);
                                    break;
                                    
                                default:
                                    if ($val == '') {
                                        header('HTTP/1.1 403 Forbidden');
                                        echo json_encode(array("errors" => __('Bad Value', 'crucible')));
                                        exit(); 
                                    }
									
                                    /* for storing in DB - fix with IE 8 workaround */
                                    $val = str_replace( array("<br />","<br/>","<br>") , "\n" , $val );	

                                    if (substr($col,0,7) == 'custom_') /* updating custom fields */
                                    {
                                        $custom_fields = array(); /* used for insert as well */
                                        $custom_count = count($this->options['field_custom']); /* used for insert as well */
                                        for ($i = 0; $i < $custom_count; $i++)
                                        {
                                            $custom_fields[$i] = $this->options['field_custom'][$i];
                                        }

                                        $custom_num = substr($col,7); /* gets the number after the _ */
                                        /* get the old custom value */
                                        $old_value = $wpdb->get_results("SELECT `custom_fields` FROM `$this->dbtable` WHERE `id`={$this->p->r} LIMIT 1");										
                                        if ($old_value && $wpdb->num_rows)
                                        {
                                            $old_value = @unserialize($old_value[0]->custom_fields);
                                            if (!is_array($old_value)) { $old_value = array(); }
                                            $custom_name = $custom_fields[$custom_num];
                                            $old_value[$custom_name] = $val;
                                            $new_value = serialize($old_value);											
                                            $update_col = esc_sql('custom_fields');
                                            $update_val = esc_sql($new_value);
                                        }
                                    }
                                    else /* updating regular fields */
                                    {									
                                        $update_col = esc_sql($col);
                                        $update_val = esc_sql($val);
                                    }

                                    $show_val = $val;
                                    
                                    break;
                            }
                            
                        }
                        
                        if ($update_col !== false && $update_val !== false) {

                           $query = "UPDATE `$this->dbtable` SET `$update_col`='$update_val' WHERE `id`={$this->p->r} LIMIT 1";
                             $wpdb->query($query);
                            echo $show_val;
                        }
                        
                        exit();
                        break;
                }
            }
			
            if ( isset($this->p->delete_reviews) && is_array($this->p->delete_reviews) && count($this->p->delete_reviews) ) {
                
                foreach ($this->p->delete_reviews as $i => $rid) {
                    $this->p->delete_reviews[$i] = intval($rid);
                }
				
                if (isset($this->p->act2)) { $this->p->action = $this->p->action2; }
				
                switch ($this->p->action) {
                    case 'bapprove':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=1 WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                    case 'bunapprove':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=0 WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                    case 'btrash':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=2 WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                    case 'bdelete':
                        $wpdb->query("DELETE FROM `$this->dbtable` WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                }
            }
			
            $this->force_update_cache(); /* update any caches */            
			$this->smar_redirect("?page=smar_view_reviews&review_status={$this->p->review_status}");
        }
        /* end - actions */
        
        /* begin - searching */
        if ($this->p->review_status == -1) {
            $sql_where = '-1=-1';
        } else {
            $sql_where = 'status='.$this->p->review_status;
        }
        
        $and_clause = '';
        if ($this->p->s != '') { /* searching */
            $this->p->s = '%'.$this->p->s.'%';
            $sql_where = '-1=-1';
            $this->p->review_status = -1;
            $and_clause = "AND (`reviewer_name` LIKE %s OR `reviewer_email` LIKE %s OR `reviewer_ip` LIKE %s OR `review_text` LIKE %s OR `review_response` LIKE %s OR `reviewer_url` LIKE %s)";
            $and_clause = $wpdb->prepare($and_clause,$this->p->s,$this->p->s,$this->p->s,$this->p->s,$this->p->s,$this->p->s);
            
            $query = "SELECT 
                `id`,
                `date_time`,
                `reviewer_name`,
                `reviewer_email`,
                `reviewer_ip`,
                `review_title`,
                `review_text`,
                `review_response`,
                `review_rating`,
                `reviewer_url`,
                `status`,
                `page_id`,
                `custom_fields`
                FROM `$this->dbtable` WHERE $sql_where $and_clause ORDER BY `id` DESC"; 
            
            $reviews = $wpdb->get_results($query);
            $total_reviews = 0; /* no pagination for searches */
        }
        /* end - searching */
        else {
			$arr_Reviews = $this->get_reviews($this->page,$per_page,$this->p->review_status);
            $reviews = $arr_Reviews[0];
            $total_reviews = $arr_Reviews[1];
        }
        $status_text = "";
        switch ($this->p->review_status)
        {
            case -1:
                $status_text = __('Submitted', 'crucible');
                break;
            case 0:
                $status_text = __('Pending', 'crucible');
                break;
            case 1:
                $status_text = __('Approved', 'crucible');
                break;
            case 2:
                $status_text = __('Trashed', 'crucible');
                break;
        }
        
        $pending_count = $wpdb->get_results("SELECT COUNT(*) AS `count_pending` FROM `$this->dbtable` WHERE `status`=0");
        $pending_count = $pending_count[0]->count_pending;
		
        $approved_count = $wpdb->get_results("SELECT COUNT(*) AS `count_approved` FROM `$this->dbtable` WHERE `status`=1");
        $approved_count = $approved_count[0]->count_approved;

        $trash_count = $wpdb->get_results("SELECT COUNT(*) AS `count_trash` FROM `$this->dbtable` WHERE `status`=2");
        $trash_count = $trash_count[0]->count_trash;
        ?>
        <div id="smar_respond_1" class="wrap">
            <div class="icon32" id="icon-edit-comments"><br /></div>
            <h2><?php _e('Reviews', 'crucible'); ?> - <?php echo sprintf(__('%s Reviews', 'crucible'), $status_text); ?></h2>
              <ul class="subsubsub">
                <li class="all"><a <?php if ($this->p->review_status == -1) { echo 'class="current"'; } ?> href="?page=smar_view_reviews&amp;review_status=-1"><?php _e('All', 'crucible'); ?></a> |</li>
                <li class="moderated"><a <?php if ($this->p->review_status == 0) { echo 'class="current"'; } ?> href="?page=smar_view_reviews&amp;review_status=0"><?php _e('Pending ', 'crucible'); ?>
                    <span class="count">(<span class="pending-count"><?php echo $pending_count;?></span>)</span></a> |
                </li>
                <li class="approved"><a <?php if ($this->p->review_status == 1) { echo 'class="current"'; } ?> href="?page=smar_view_reviews&amp;review_status=1"><?php _e('Approved', 'crucible'); ?>
                    <span class="count">(<span class="pending-count"><?php echo $approved_count;?></span>)</span></a> |
                </li>
                <li class="trash"><a <?php if ($this->p->review_status == 2) { echo 'class="current"'; } ?> href="?page=smar_view_reviews&amp;review_status=2"><?php _e('Trash', 'crucible'); ?>
                    <span class="count">(<span class="pending-count"><?php echo $trash_count;?></span>)</span></a>
                </li>
              </ul>

              <form method="GET" action="" id="search-form" name="search-form">
                  <p class="search-box">
                      <?php if ($this->p->s_orig): ?><span style='color:#c00;font-weight:bold;'><?php _e('RESULTS FOR: ', 'crucible'); ?></span><br /><?php endif; ?>
                      <label for="comment-search-input" class="screen-reader-text"><?php _e('Search Reviews:', 'crucible'); ?></label> 
                      <input type="text" value="<?php echo $this->p->s_orig; ?>" name="s" id="comment-search-input" />
                      <input type="hidden" name="page" value="smar_view_reviews" />
                      <input type="submit" class="button" value="<?php _e('Search Reviews', 'crucible'); ?>" />
                  </p>
              </form>

              <form method="POST" action="?page=smar_view_reviews" id="comments-form" name="comments-form">
              <input type="hidden" name="review_status" value="<?php echo $this->p->review_status; ?>" />
              <div class="tablenav">
                <div class="alignleft actions">
                      <select name="action">
                            <option selected="selected" value="-1"><?php _e('Bulk Actions', 'crucible'); ?></option>
                            <option value="bunapprove"><?php _e('Unapprove', 'crucible'); ?></option>
                            <option value="bapprove"><?php _e('Approve', 'crucible'); ?></option>
                            <option value="btrash"><?php _e('Move to Trash', 'crucible'); ?></option>
                            <option value="bdelete"><?php _e('Delete Forever', 'crucible'); ?></option>
                      </select>&nbsp;
                      <input type="submit" class="button-secondary apply" name="act" value="<?php _e('Apply', 'crucible'); ?>" id="doaction" /></div><br class="clear" /></div> <div class="clear"></div><table cellspacing="0" class="widefat comments fixed"><thead><tr><th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th><th style="" class="manage-column column-author" id="author" scope="col"><?php _e('Author', 'crucible'); ?></th><th style="" class="manage-column column-comment" id="comment" scope="col"><?php _e('Review', 'crucible'); ?></th></tr>
                </thead>
                <tfoot>
                  <tr>
                    <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox" /></th>
                    <th style="" class="manage-column column-author" scope="col"><?php _e('Author', 'crucible'); ?></th>
                    <th style="" class="manage-column column-comment" scope="col"><?php _e('Review', 'crucible'); ?></th>
                  </tr>
                </tfoot>
                <tbody class="list:comment" id="the-comment-list">
                  <?php
                  if (count($reviews) == 0) { ?>
                        <tr><td colspan="3" align="center"><br />
<?php echo sprintf(__('There are no %s reviews yet.', 'crucible'), $status_text); ?> <br /><br /></td></tr>
                      <?php
                  }
                  foreach ($reviews as $review) {
                      $rid = $review->id;
                      $update_path = get_admin_url()."admin-ajax.php?page=smar_view_reviews&r=$rid&action=update_field";
                      $hash = md5( strtolower( trim( $review->reviewer_email ) ) );
                      $review->review_title = stripslashes($review->review_title);
                      $review->review_text = stripslashes($review->review_text);
                      $review->review_response = stripslashes($review->review_response);
                      $review->reviewer_name = stripslashes($review->reviewer_name);
                      if ($review->reviewer_name == '') { $review->reviewer_name = __('Anonymous', 'crucible'); }
                      $review_text = nl2br($review->review_text);
                      $review_text = str_replace( array("\r\n","\r","\n") , "" , $review_text );
                      $review_response = nl2br($review->review_response);
                      $review_response = str_replace( array("\r\n","\r","\n") , "" , $review_response );
					  
                      // @test without this $page = get_post($review->page_id);

					  ?>
                      <tr class="approved" id="review-<?php echo $rid;?>">
                        <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $rid;?>" name="delete_reviews[]" /></th>
                        <td class="author column-author">
                            <img width="32" height="32" class="avatar avatar-32 photo" src=
                            "http://1.gravatar.com/avatar/<?php echo $hash; ?>?s=32&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D32&amp;r=G"
                            alt="" />&nbsp;<span style="font-weight:bold;" class="best_in_place" data-url='<?php echo $update_path; ?>' data-object='json' data-attribute='reviewer_name'><?php echo $review->reviewer_name; ?></span>
                            <br />
                            <a href="<?php echo $review->reviewer_url; ?>"><?php echo $review->reviewer_url; ?></a><br />
                            <a href="mailto:<?php echo $review->reviewer_email; ?>"><?php echo $review->reviewer_email; ?></a><br />
                            <a href="?page=smar_view_reviews&amp;s=<?php echo $review->reviewer_ip; ?>"><?php echo $review->reviewer_ip; ?></a><br />
                            <?php
                            $custom_count = count($this->options['field_custom']); /* used for insert as well */
                            $custom_unserialized = @unserialize($review->custom_fields);
                            if ($custom_unserialized !== false)
                            {							
                                for ($i = 0; $i < $custom_count; $i++)
                                {
                                    $custom_field_name = $this->options['field_custom'][$i];
                                    if ( isset($custom_unserialized[$custom_field_name]) ) {
                                        $custom_value = $custom_unserialized[$custom_field_name];
                                        if ($custom_value != '')
                                        {
                                            echo "$custom_field_name: <span class='best_in_place' data-url='$update_path' data-object='json' data-attribute='custom_$i'>$custom_value</span><br />";
                                        }
                                    }
                                }
                            }
                            ?>
                            <div style="margin-left:-4px;">
                                <div style="height:22px;" class="best_in_place" 
                                     data-collection='[[1,"Rated 1 Star"],[2,"Rated 2 Stars"],[3,"Rated 3 Stars"],[4,"Rated 4 Stars"],[5,"Rated 5 Stars"]]' 
                                     data-url='<?php echo $update_path; ?>' 
                                     data-object='json'
                                     data-attribute='review_rating' 
                                     data-callback='make_stars_from_rating'
                                     data-type='select'><?php 
									 echo $this->output_rating($review->review_rating,false); // @test
									 
									 ?></div>
								</div>
                        </td>
                        <td class="comment column-comment">
                          <div class="smar-submitted-on">
                            <span class="best_in_place" data-url='<?php echo $update_path; ?>' data-object='json' data-attribute='date_time'>
<?php echo date(__('m/d/Y g:i a', 'crucible'),strtotime(__($review->date_time, 'crucible'))); ?>
                            </span>
                            <?php if ($review->status == 1) : 
							
							
							
								// @test hide jumplink if on a search page...
								if ($this->p->s == '') {
									/* not searching */
								
									?>[<a target="_blank" href="<?php 
							
									echo $this->get_jumplink_for_review($review,$this->page);// @todo get page a diff method. ?>"><?php _e('View Review on Page', 'crucible'); ?></a>]<?php
								}
							endif; ?>
                          </div>
                          <p>
                              <span style="font-size:13px;font-weight:bold;"><?php _e('Title:', 'crucible'); ?>&nbsp;</span>
                              <span style="font-size:14px; font-weight:bold;" 
                                    class="best_in_place" 
                                    data-url='<?php echo $update_path; ?>' 
                                    data-object='json'
                                    data-attribute='review_title'><?php echo $review->review_title; ?></span>
                              <br /><br />
                              <div class="best_in_place" 
                                    data-url='<?php echo $update_path; ?>' 
                                    data-object='json'
                                    data-attribute='review_text' 
                                    data-callback='callback_review_text'
                                    data-type='textarea'><?php echo $review_text; ?></div>
                             <div style="font-size:13px;font-weight:bold;">
                                 <br />
                                 <?php _e('Official Response:', 'crucible'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                 <span style="font-size:11px;font-style:italic;"><?php _e('Leave this blank if you do not want it to be public', 'crucible'); ?></span>
                             </div>
                             <div class="best_in_place" 
                                    data-url='<?php echo $update_path; ?>'
                                    data-object='json'
                                    data-attribute='review_response' 
                                    data-callback='callback_review_text'
                                    data-type='textarea'><?php echo $review_response; ?></div>
                          </p>
                          <div class="row-actions">
                            <span class="approve <?php if ($review->status == 0 || $review->status == 2) { echo 'smar_show'; } else { echo 'smar_hide'; }?>"><a title="<?php _e( 'Mark as Approved', 'crucible' ); ?>"
                            href="?page=smar_view_reviews&amp;action=approvereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            <?php _e('Mark as Approved', 'crucible'); ?></a>&nbsp;|&nbsp;</span>
                            <span class="unapprove <?php if ($review->status == 1 || $review->status == 2) { echo 'smar_show'; } else { echo 'smar_hide'; }?>"><a title="<?php _e( 'Mark as Unapproved', 'crucible' ); ?>"
                            href="?page=smar_view_reviews&amp;action=unapprovereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            <?php _e('Mark as Unapproved', 'crucible'); ?></a><?php if ($review->status != 2): ?>&nbsp;|&nbsp;<?php endif; ?></span>
                            <span class="trash <?php if ($review->status == 2) { echo 'smar_hide'; } else { echo 'smar_show'; }?>"><a title="<?php _e( 'Move to Trash', 'crucible' ); ?>" 
                            href= "?page=smar_view_reviews&amp;action=trashreview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            <?php _e('Move to Trash', 'crucible'); ?></a><?php if ($review->status != 2): ?>&nbsp;|&nbsp;<?php endif; ?></span>
                            <span class="trash <?php if ($review->status == 2) { echo 'smar_hide'; } else { echo 'smar_show'; }?>"><a title="<?php _e( 'Delete Forever', 'crucible' ); ?>" 
                            href= "?page=smar_view_reviews&amp;action=deletereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            <?php _e('Delete Forever', 'crucible'); ?></a></span>
                          </div>
                        </td>
                      </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>

              <div class="tablenav">
                <div class="alignleft actions" style="float:left;">
                      <select name="action2">
                            <option selected="selected" value="-1"><?php _e('Bulk Actions', 'crucible'); ?></option>
                            <option value="bunapprove"><?php _e('Unapprove', 'crucible'); ?></option>
                            <option value="bapprove"><?php _e('Approve', 'crucible'); ?></option>
                            <option value="btrash"><?php _e('Move to Trash', 'crucible'); ?></option>
                            <option value="bdelete"><?php _e('Delete Forever', 'crucible'); ?></option>
                      </select>&nbsp;
                      <input type="submit" class="button-secondary apply" name="act2" value="<?php _e('Apply', 'crucible'); ?>" id="doaction2" />
                </div>
                <div class="alignleft actions" style="float:left;padding-left:20px;"><?php 
				
				// @test this output
				echo $this->pagination($total_reviews);
				?></div>  
                <br class="clear" />
              </div>
            </form>
            <div id="ajax-response"></div>
          </div>
        <?php
    } // end admin_view_reviews	
	
} // end class
$SMARTESTReviewsBusiness = SMARTESTReviewsBusiness::get_instance();
/* get widget */
include_once('widget-testimonial.php');
add_shortcode( 'smartest_reviews', array( $SMARTESTReviewsBusiness, 'reviews_shortcode' ) );
add_shortcode( 'aggregate_rating', array( $SMARTESTReviewsBusiness, 'aggregate_footer_func' ) );
?>