<?php


class Tribe__Events__Community__Event_Form {
	protected $event = NULL;
	protected $event_id = 0;
	protected $required_fields = array();
	protected $error_fields = array();

	public function __construct( $event, $required_fields = array(), $error_fields = array() ) {
		$this->event = $event;
		if ( isset($event->ID) ) {
			$this->event_id = $event->ID;
		}
		$this->required_fields = $required_fields;
		$this->error_fields = $error_fields;
	}

	public function render() {
		$edit_template = $this->get_template_path();
		$this->setup_hooks();
		ob_start();
		do_action( 'tribe_events_community_form', $this->event_id, $this->event, $edit_template );
		$output = ob_get_clean();
		$this->clear_hooks();
		return $output;
	}

	protected function get_template_path() {
		return TribeEventsTemplates::getTemplateHierarchy('community/edit-event', array( 'disable_view_check' => true ) );
	}

	protected function setup_hooks() {
		// hooks that will need to be removed after we're done rendering
		add_action( 'tribe_community_events_field_has_error', array( $this, 'indicate_field_errors' ), 10, 2 );
		add_filter( 'tribe_display_event_organizer_dropdown_id', array( $this, 'filter_selected_organizer_id' ), 10, 1 );
		add_filter( 'tribe_display_event_venue_dropdown_id', array( $this, 'filter_selected_venue_id' ), 10, 1 );

		if ( !empty($_POST) ) {
			add_filter( 'tribe_get_event_website_url', array( $this, 'filter_website_url_value' ), 10, 2 );
			add_filter( 'tribe_community_custom_field_value', array( $this, 'filter_custom_field_value' ), 10, 2 );

			add_filter( 'tribe_get_organizer', array( $this, 'filter_organizer_value' ), 10, 1 );
			add_filter( 'tribe_get_organizer_phone', array( $this, 'filter_organizer_phone' ), 10, 1 );
			add_filter( 'tribe_get_organizer_website_url', array( $this, 'filter_organizer_website' ), 10, 1 );
			add_filter( 'tribe_get_organizer_email', array( $this, 'filter_organizer_email' ), 10, 1 );

			add_filter( 'tribe_get_venue', array( $this, 'filter_venue_name' ), 10, 1 );
			add_filter( 'tribe_get_phone', array( $this, 'filter_venue_phone' ), 10, 1 );
			add_filter( 'tribe_get_address', array( $this, 'filter_venue_address' ), 10, 1 );
			add_filter( 'tribe_get_city', array( $this, 'filter_venue_city' ), 10, 1 );
			add_filter( 'tribe_get_province', array( $this, 'filter_venue_province' ), 10, 1 );
			add_filter( 'tribe_get_state', array( $this, 'filter_venue_state' ), 10, 1 );
			add_filter( 'tribe_get_country', array( $this, 'filter_venue_country' ), 10, 1 );
			add_filter( 'tribe_get_zip', array( $this, 'filter_venue_zip' ), 10, 1 );
		}

		// hooks that are fine to leave in place
		add_action( 'tribe_events_community_form', array($this, 'print_form'), 10, 3 );

		remove_filter( 'the_content', 'do_shortcode', 11 );

		//get data from $_POST and override core function
		add_filter( 'tribe_get_hour_options', array( $this, 'getHours' ), 10, 3 );
		add_filter( 'tribe_get_minute_options', array( $this, 'getMinutes' ), 10, 3 );
		add_filter( 'tribe_get_meridian_options', array( $this, 'getMeridians' ), 10, 3 );


		//turn off upsell -- this is public after all
		remove_action( 'tribe_events_cost_table', array( TribeEvents::instance(), 'maybeShowMetaUpsell' ) );

		if ( class_exists( 'Event_Tickets_PRO' ) ) {
			// Remove the eventbrite method hooked into the event form, if it exists.
			remove_action( 'tribe_events_cost_table', array( Event_Tickets_PRO::instance(), 'eventBriteMetaBox' ), 1 );
		}

		if ( class_exists('TribeEventsPro') ) {
			remove_action( 'tribe_events_date_display', array( 'TribeEventsRecurrenceMeta', 'loadRecurrenceData' ) );
			add_action( 'tribe_events_date_display', array( $this, 'loadRecurrenceData' ) );
		}
	}

	public function clear_hooks() {
		remove_action( 'tribe_community_events_field_has_error', array( $this, 'indicate_field_errors' ), 10, 2 );
	}


	/**
	 * Return event start/end hours.
	 *
	 * @param string $hours The event hours.
	 * @param string $date The date.
	 * @param bool $isStart Is it the project start?
	 * @return string The event's hours.
	 */
	public function getHours( $hours, $date, $isStart ) {

		if ( $isStart ) {
			if ( isset( $_REQUEST[ 'EventStartHour' ] ) )
				$hour = intval( $_REQUEST[ 'EventStartHour' ] );
		} else {
			if ( isset( $_REQUEST[ 'EventEndHour' ] ) )
				$hour = intval( $_REQUEST[ 'EventEndHour' ] );
		}

		if ( isset( $hour ) ) {
			return $hour;
		} else {
			return $hours;
		}

	}

	/**
	 * Return event start/end minutes.
	 *
	 * @param string $minutes The event minutes.
	 * @param string $date The date.
	 * @param bool $isStart Is it the project start?
	 * @return string The event's minutes.
	 */
	public function getMinutes( $minutes, $date, $isStart ) {

		if ( $isStart ) {
			if ( isset( $_REQUEST[ 'EventStartMinute' ] ) )
				$minute = intval( $_REQUEST[ 'EventStartMinute' ] );
		} else {
			if ( isset( $_REQUEST[ 'EventEndMinute' ] ) )
				$minute = intval( $_REQUEST[ 'EventEndMinute' ] );
		}

		if ( isset( $minute ) ) {
			return $minute;
		} else {
			return $minutes;
		}

	}

	/**
	 * Return event start/end meridian.
	 *
	 * @param string $meridians The event meridians.
	 * @param string $date The date.
	 * @param bool $isStart Is it the project start?
	 * @return string The event's meridian.
	 */
	public function getMeridians( $meridians, $date, $isStart ) {

		if ( $isStart ) {
			if ( isset( $_REQUEST[ 'EventStartMeridian' ] ) )
				$meridian = $_REQUEST[ 'EventStartMeridian' ];
		} else {
			if ( isset( $_REQUEST[ 'EventEndMeridian' ] ) )
				$meridian = $_REQUEST[ 'EventEndMeridian' ];
		}

		if ( isset( $meridian ) ) {
			return $meridian;
		} else {
			return $meridians;
		}
	}



	/**
	 * Load recurrence data for ECP.
	 *
	 * @param int $postId The event id.
	 * @return void
	 * @author Nick Ciske
	 * @since 1.0
	 */
	public function loadRecurrenceData( $postId ) {
		$tce = TribeCommunityEvents::instance();
		$context = $tce->getContext();
		$tribe_event_id = $context['id'];
		include TribeEventsTemplates::getTemplateHierarchy( 'community/modules/recurrence' );
	}


	/**
	 * Includes the specified template.
	 *
	 * @param int $tribe_event_id The event id.
	 * @param object $event The event object.
	 * @param string $template The template path.
	 * @return void
	 */
	public function print_form( $tribe_event_id, $event, $template ) {
		include $template;
	}

	public function indicate_field_errors( $error, $field ) {
		return $error || in_array($field, $this->error_fields);
	}

	public function filter_website_url_value( $url, $post_id ) {
		return isset($_POST['EventURL']) ? stripslashes($_POST['EventURL']) : $url;
	}

	public function filter_custom_field_value( $value, $fieldname ) {
		return isset($_POST[$fieldname]) ? stripslashes($_POST[$fieldname]) : $value;
	}

	public function filter_selected_organizer_id( $organizer_id ) {
		$organizer_id = isset($_POST['organizer']['OrganizerID']) ? stripslashes($_POST['organizer']['OrganizerID']) : $organizer_id;
		if ( empty($this->event_id) && empty($organizer_id) && !isset($_POST['organizer']['OrganizerID']) ) {
			$organizer_id = TribeCommunityEvents::instance()->getOption( 'defaultCommunityOrganizerID' );
		}
		return $organizer_id;
	}

	public function filter_organizer_value( $name ){
		return isset($_POST['organizer']['Organizer']) ? stripslashes($_POST['organizer']['Organizer']) : $name;
	}

	public function filter_organizer_phone( $phone ) {
		return isset($_POST['organizer']['Phone']) ? stripslashes($_POST['organizer']['Phone']) : $phone;
	}

	public function filter_organizer_website( $website ) {
		return isset($_POST['organizer']['Website']) ? stripslashes($_POST['organizer']['Website']) : $website;
	}

	public function filter_organizer_email( $email ) {
		return isset($_POST['organizer']['Email']) ? stripslashes($_POST['organizer']['Email']) : $email;
	}

	public function filter_selected_venue_id( $venue_id ) {
		$venue_id = isset($_POST['venue']['VenueID']) ? stripslashes($_POST['venue']['VenueID']) : $venue_id;
		if ( empty($this->event_id) && empty($venue_id) && !isset($_POST['venue']['VenueID']) ) {
			$venue_id = TribeCommunityEvents::instance()->getOption( 'defaultCommunityVenueID' );
		}
		return $venue_id;
	}

	public function filter_venue_name( $name ) {
		return isset($_POST['venue']['Venue']) ? stripslashes($_POST['venue']['Venue']) : $name;
	}

	public function filter_venue_phone( $phone ) {
		return isset($_POST['venue']['Phone']) ? stripslashes($_POST['venue']['Phone']) : $phone;
	}

	public function filter_venue_address( $address ) {
		return isset($_POST['venue']['Address']) ? stripslashes($_POST['venue']['Address']) : $address;
	}

	public function filter_venue_city( $city ) {
		return isset($_POST['venue']['City']) ? stripslashes($_POST['venue']['City']) : $city;
	}

	public function filter_venue_province( $province ) {
		return isset($_POST['venue']['Province']) ? stripslashes($_POST['venue']['Province']) : $province;
	}

	public function filter_venue_state( $state ) {
		return isset($_POST['venue']['State']) ? stripslashes($_POST['venue']['State']) : $state;
	}

	public function filter_venue_country( $country ) {
		return isset($_POST['venue']['Country']) ? stripslashes($_POST['venue']['Country']) : $country;
	}

	public function filter_venue_zip( $zip ) {
		return isset($_POST['venue']['Zip']) ? stripslashes($_POST['venue']['Zip']) : $zip;
	}
} 