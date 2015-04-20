<?php

require_once( dirname( __FILE__ ) . '/Abstract_Captcha.php' );


class Tribe__Events__Community__Captcha__Recaptcha
	extends Tribe__Events__Community__Captcha__Abstract_Captcha {

	/**
	 * The user must provide values for captcha keys in order to display reCAPTCHA on the front end.
	 * An empty value in either field will prevent the reCAPTCHA from rendering.
	 * If both fields are complete, the reCAPTCHA will automatically appear.
	 */
	protected function get_settings_fields() {
		return array(

			'recaptcha-heading' => array(
				'type' => 'heading',
				'label' => __( 'reCAPTCHA API Keys', 'tribe-events-community' ),
			),

			'recaptcha-info' => array(
				'type' => 'html',
				'html' => '<p>'. __( 'Provide reCAPTCHA API keys for both fields to enable reCAPTCHA on your community events form.', 'tribe-events-community' ) .'<br><br><em>'.__('Note: reCAPTCHA only appears for users who are not logged in.').'</em></p>',
			),

			'recaptchaPublicKey' => array(
				'type' 			  => 'text',
				'label' 		  => __( 'Site Key', 'tribe-events-community' ),
				'tooltip' 		  => sprintf( __( 'Get your Site Key at %s', 'tribe-events-community' ), '<a href="'.$this->registration_url().'" target="_blank">'.$this->registration_url().'</a>' ),
				'default' 		  => '',
				'validation_type' => 'html',
				'can_be_empty' 	  => true,
				'parent_option'   => TribeCommunityEvents::OPTIONNAME,
				'size' 			  => 'large',
			),

			'recaptchaPrivateKey' => array(
				'type' 			  => 'text',
				'label' 		  => __( 'Secret Key', 'tribe-events-community' ),
				'tooltip' 		  => sprintf( __( 'Get your Secret Key at %s', 'tribe-events-community' ), '<a href="'.$this->registration_url().'" target="_blank">'.$this->registration_url().'</a>' ),
				'default' 		  => '',
				'validation_type' => 'html',
				'can_be_empty' 	  => true,
				'parent_option'   => TribeCommunityEvents::OPTIONNAME,
				'size' 			  => 'large',
			),
		);
	}

	/**
	 * Add recaptcha settings to the front-end JS
	 *
	 * @return void
	 */
	public function enqueue_scripts_and_styles() {
		if ( !$this->settings_valid() ) {
			return;
		}
		$locale = substr( get_locale(), 0, 2 );
		$recaptcha_options = array(
			'theme' => 'white',
			'lang' => $locale,
		);
		$recaptcha_options = apply_filters( 'tribe_community_events_recaptcha_widget_options', $recaptcha_options );
		wp_localize_script( TribeEvents::POSTTYPE . '-community', 'RecaptchaOptions', $recaptcha_options);
	}

	/**
	 * @return string The captcha form
	 */
	protected function get_captcha_form() {
		if ( !$this->settings_valid() ) {
			return '';
		}

		$this->load_recaptchaphp_library();
		$public_key = $this->public_key();
		$captcha = recaptcha_get_html( $public_key, NULL, is_ssl() );
		ob_start();
		tribe_get_template_part( 'community/modules/captcha', null, array( 'captcha' => $captcha ) );
		$form = ob_get_clean();
		return $form;
	}

	protected function get_fieldname_whitelist() {
		return $this->get_recaptcha_field_names();
	}

	protected function get_required_fields() {
		if ( $this->showing_captcha() ) {
			return $this->get_recaptcha_field_names();
		} else {
			return array();
		}
	}

	/**
	 * Send the captcha through the recaptcha library for validation
	 *
	 * @param array $submission
	 *
	 * @return bool
	 */
	public function validate_captcha( $submission ) {
		if ( empty( $submission['recaptcha_challenge_field'] ) ||  empty( $submission['recaptcha_response_field'] ) ) {
			return FALSE;
		}
		$this->load_recaptchaphp_library();
		$private_key = $this->private_key();
		$response = recaptcha_check_answer(
			$private_key,
			$_SERVER[ 'REMOTE_ADDR' ],
			$submission[ 'recaptcha_challenge_field' ],
			$submission[ 'recaptcha_response_field' ]
		);
		return $response->is_valid;
	}

	protected function get_recaptcha_field_names() {
		return array( 'recaptcha_challenge_field', 'recaptcha_response_field' );
	}

	/**
	 * @return string The URL where users can register for recaptcha and retrieve public and private keys
	 */
	protected function registration_url() {
		/**
		 * The php library we're using to interact with captcha does
		 * have a library for doing this, but it doesn't seem to work as intended.
		 * It purports to be able to pass params to reCAPTCHA to prefill the "domains"
		 * field, but it doesn't seem to work.  So, let's just use this instead.
		 */
		return apply_filters( 'tribe_community_events_recaptcha_registration_url', 'https://www.google.com/recaptcha/admin#createsite' );
	}

	protected function settings_valid() {
		$public = $this->public_key();
		$private = $this->private_key();
		if ( empty( $public ) || empty( $private ) ) {
			return FALSE;
		}
		return TRUE;
	}

	protected function public_key() {
		return TribeCommunityEvents::instance()->getOption('recaptchaPublicKey', '');
	}

	protected function private_key() {
		return TribeCommunityEvents::instance()->getOption('recaptchaPrivateKey', '');
	}

	protected function load_recaptchaphp_library() {
		static $loaded = FALSE;
		if ( !$loaded ) {
			$loaded = TRUE;
			require_once( TribeCommunityEvents::instance()->pluginPath . 'vendor/recaptcha-php/recaptchalib.php' );
		}
	}
} 