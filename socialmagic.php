<?php
/*
 * SocialMagic. Create fixed social links with ease.
 *
 * Plugin Name: SocialMagic
 * Plugin URI:  https://www.alexperez.ninja
 * Description: Simple fixed social links insert to add social functionality to any page.
 * Version:     1.0
 * Author:      Alex Perez Development
 * Author URI:  https://www.alexperez.ninja
 * Copyright:   2016 Alex Perez Development
 */

class SocialMagicPlugin {

		/*********
		* version
		*********/

		public $version = '1.0.0';

	 /*********
     * Init
   *********/
    public static function init() {

        $socialmagic = new self();

    }

     /*************
     * Constructor
     *************/
    public function __construct() {

				$this->define_constants();
				$this->register_shortcode();
				$this->setup_actions();
				$this->enqueue_scripts();

    }

		/**********
		* Define SocialMagic constants
		**********/

		private function define_constants() {

			define( 'SOCIALMAGIC_PATH', trailingslashit( plugins_url( 'socialmagic' ) ) );
			define( 'SOCIALMAGIC_VERSION', $this->version );

		}

    /***************************************
     * register the [socialmagic] shortcode
     **************************************/
    private function register_shortcode() {

        add_shortcode( 'socialmagic', array( $this, 'setup_shortcode' ) );

    }

		private function enqueue_scripts() {

			function socialmagic_enqueue() {

				wp_enqueue_style( 'socialmagic', SOCIALMAGIC_PATH . 'css/socialmagic.css', array(), SOCIALMAGIC_VERSION, 'all' );
				wp_enqueue_style( 'fontawesome', '//netdna.bootstrapcdn.com/font-awesome/latest/css/font-awesome.css', array(), SOCIALMAGIC_VERSION, 'all' );

			}

			add_action( 'wp_enqueue_scripts', 'socialmagic_enqueue' );

		}

		private function setup_actions() {

			add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 9001 );

		}

		public function register_admin_menu() {

			$title = apply_filters( 'socialmagic_menu_title', 'SocialMagic' );

			$capability = apply_filters( 'socialmagic_capability', 'edit_others_posts' );

			$page = add_menu_page( $title, $title, $capability, 'socialmagic', array(
				$this, 'render_admin_page'
			), SOCIALMAGIC_PATH . 'images/socialmagic.png', 9501 );

		}

		public function render_admin_page() {
			?>
			<div class="wrap socialmagic">
				<form action="form-action">
					<input>
					<input>
				</form>
			</div>


			<?php
		}

		/***************
		* shortcode construction
		***************/

     public function setup_shortcode( $atts ) {

        extract( shortcode_atts( array(
            'id' => false,
            'restrict_to' => false
        ), $atts, 'socialmagic' ) );


        if ( ! $id ) {
            return false;
        }

        // handle [socialmagic id=123 restrict_to=home]
        if ($restrict_to && $restrict_to == 'home' && ! is_front_page()) {
            return;
        }

        if ($restrict_to && $restrict_to != 'home' && ! is_page( $restrict_to ) ) {
            return;
        }

        // we have an ID to work with
        $social = get_post( $id );




        return '<ul class="socialMagicMenu">
								<li id="fuckinFacebook"><a href="www.facebook.com"><img src="' . SOCIALMAGIC_PATH . 'images/facebook.png' . '"></a></li>
								<li id="fuckinTwitter"><a href="www.twitter.com"><img src="' . SOCIALMAGIC_PATH . 'images/email.png' . '"></a></li>
								<li id="fuckinSomeothershit"><a href="www.linkedin.com"><img src="' . SOCIALMAGIC_PATH . 'images/instagram.png' . '"></a></li>
								</ul>';
    }
}

add_action('plugins_loaded', array('SocialMagicPlugin', 'init'), 10);
