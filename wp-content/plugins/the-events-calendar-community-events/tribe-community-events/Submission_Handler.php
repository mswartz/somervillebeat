<?php


class Tribe__Events__Community__Submission_Handler {
	/**
	 * @var TribeCommunityEvents
	 */
	protected $community = NULL;
	protected $submission = array();
	protected $original_submission = array();
	protected $event_id = 0;
	protected $valid = NULL;
	protected $messages = array();
	protected $invalid_fields = array();

	public function __construct( $submission, $event_id ) {
		$this->community = TribeCommunityEvents::instance();
		$this->original_submission = $submission;
		$submission[ 'ID' ] = $event_id;
		$this->submission = $submission;
		require_once( 'Submission_Scrubber.php' );
		$scrubber = new Tribe__Events__Community__Submission_Scrubber($this->submission);
		$this->submission = $scrubber->scrub();
		$this->event_id = $event_id;
	}

	public function validate() {
		if ( isset($this->valid) ) {
			return $this->valid;
		}

		$this->valid = TRUE;

		// show no sympathy for spammers
		if ( !is_user_logged_in() ) {
			$this->community->spam_check( $this->submission ); // exits on failure
		}

		if ( !$this->validate_submission_has_required_fields($this->submission) ) {
			$this->valid = FALSE;
		}

		if ( !$this->validate_field_contents($this->submission) ) {
			$this->valid = FALSE;
		}

		$this->valid = apply_filters( 'tribe_community_events_validate_submission', $this->valid, $this->submission, $this );
		return $this->valid;
	}

	/**
	 * Get the sanitized submission array
	 *
	 * @return array
	 */
	public function get_submission() {
		return $this->submission;
	}

	public function save() {
		if ( $this->event_id ) {
			$saved = TribeEventsAPI::updateEvent( $this->event_id, $this->submission );
			if ( $saved ) {
				$this->add_message( __( 'Event updated.', 'tribe-events-community' ) . $this->community->get_view_edit_links($this->event_id) );
				$this->add_message( '<a href="' . $this->community->getUrl( 'add' ) . '">' . __( 'Submit another event', 'tribe-events-community' ) . '</a>' );
				do_action( 'tribe_community_event_updated', $this->event_id );
			} else {
				$this->add_message( __( 'There was a problem saving your event, please try again.', 'tribe-events-community' ), 'error' );
			}
		} else {
			$saved = TribeEventsAPI::createEvent( $this->submission );
			if ( $saved ) {
				$this->event_id = $saved;
				$this->add_message( __( 'Event submitted.', 'tribe-events-community' ) . $this->community->get_view_edit_links($this->event_id) );
				$this->add_message( '<a href="' . $this->community->getUrl( 'add' ) . '">' . __( 'Submit another event', 'tribe-events-community' ) . '</a>' );
				do_action( 'tribe_community_event_created', $this->event_id );
			} else {
				$this->add_message( __( 'There was a problem submitting your event, please try again.', 'tribe-events-community' ), 'error' );
			}
		}
		$this->save_submitted_attachment($this->event_id);

		// Logged out or underprivileged users will not have terms automatically added during wp_insert_post
		if ( isset( $this->submission['tax_input'] ) ) {
			foreach ( (array)$this->submission['tax_input'] as $taxonomy => $terms ) {
				$taxonomy_obj = get_taxonomy( $taxonomy );
				if ( !current_user_can($taxonomy_obj->cap->assign_terms) ) {
					wp_set_post_terms( $this->event_id, $terms, $taxonomy, TRUE );
				}
			}
		}
		return $this->event_id;
	}

	protected function save_submitted_attachment( $parent_id ) {
		// TODO: we still have to use the global here for now
		if ( isset( $_FILES['event_image']['name'] ) && !empty( $_FILES['event_image']['name'] ) ) {
			return $this->community->insert_attachment( 'event_image', $parent_id, true );
		}
		return 0;
	}

	protected function validate_submission_has_required_fields( $submission ) {
		$valid = TRUE;
		foreach ( $this->community->required_fields_for_submission() as $key ) {
			if ( !$this->submission_has_value_for_key($submission, $key) ) {
				$message = __('%s is required', 'tribe-events-community');
				$message = sprintf( $message, $this->get_field_label($key) );
				$this->add_message($message, 'error');
				$valid = FALSE;
				$this->invalid_fields[] = $key;
			}
		}
		return $valid;
	}

	protected function submission_has_value_for_key( $submission, $key ) {
		switch ( $key ) {
			case 'venue':
				if ( empty($submission['Venue']) ) {
					return FALSE;
				}
				if ( !empty($submission['Venue']['VenueID']) ) {
					return TRUE;
				}
				if ( !empty($submission['Venue']['Venue']) ) {
					return TRUE;
				}
				return FALSE;
			case 'organizer':
				if ( empty($submission['Organizer']) ) {
					return FALSE;
				}
				if ( !empty($submission['Organizer']['OrganizerID']) ) {
					return TRUE;
				}
				if ( !empty($submission['Organizer']['Organizer']) ) {
					return TRUE;
				}
				return FALSE;
			case 'event_image':
				if ( $this->event_id && has_post_thumbnail($this->event_id) ) {
					return TRUE;
				}
				$attachment = $this->get_attachment_array();
				return !empty($attachment['name']);
			default:
				return !empty($submission[$key]);
		}
	}

	protected function get_field_label( $field ) {
		switch ( $field ) {
			case 'post_title':
				$label = __('Event Title', 'tribe-events-community');
				break;
			case 'post_content':
				$label = __('Event Description', 'tribe-events-community');
				break;
			case 'venue':
				$label = tribe_get_venue_label_singular();
				break;
			case 'organizer':
				$label = tribe_get_organizer_label_singular();
				break;
			default:
				if ( strpos( $field, '_ecp_custom_' ) === 0 ) {
					$label = $this->get_custom_field_label($field);
				} else {
					$label = $this->format_field_name_as_label($field);
				}
				break;
		}
		return apply_filters( 'tribe_community_form_field_label', $label, $field );
	}

	protected function format_field_name_as_label( $field ) {
		$regex = '/(?#! splitCamelCase Rev:20140412)
    # Split camelCase "words". Two global alternatives. Either g1of2:
      (?<=[a-z])      # Position is after a lowercase,
      (?=[A-Z])       # and before an uppercase letter.
    | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
      (?=[A-Z][a-z])  # and before upper-then-lower case.
    /x';
		$parts = preg_split($regex, $field);
		$label = implode(' ', $parts);
		$label = str_replace('_', ' ', $label);
		$label = ucwords($label);
		return $label;
	}

	protected function get_custom_field_label( $name ) {
		$fields = tribe_get_option('custom-fields');
		foreach ( $fields as $field ) {
			if ( $field['name'] == $name ) {
				return $field['label'];
			}
		}
		return $name;
	}

	protected function validate_field_contents( $submission ) {
		$valid = TRUE;
		foreach ( $submission as $key => $value ) {
			if ( !$this->is_field_valid($key, $value) ) {
				$message = __('Invalid value for %s', 'tribe-events-community');
				$message = sprintf( $message, $this->get_field_label($key) );
				$this->add_message($message, 'error');
				$valid = FALSE;
			}
		}
		if ( $attachment = $this->get_attachment_array() ) {
			if ( $attachment && !in_array($attachment['type'], $this->image_mime_types()) ) {
				$message = __('Images must be png, jpg, or gif');
				$this->add_message($message, 'error');
				$valid = FALSE;
			}
		}
		return $valid;
	}

	protected function image_mime_types() {
		return array(
			'image/png',
			'image/jpeg',
			'image/gif',
		);
	}

	protected function get_attachment_array() {
		// TODO: we still have to use the global here for now
		if ( empty($_FILES) || empty($_FILES['event_image']) || empty($_FILES['event_image']['name']) ) {
			return array();
		}
		return $_FILES['event_image'];
	}

	protected function is_field_valid( $key, $value ) {
		$valid = TRUE;
		$valid = apply_filters( 'tribe_community_is_field_valid', $valid, $key, $value );
		return $valid;
	}

	public function get_messages() {
		return $this->messages;
	}

	public function add_message( $message, $type = 'update' ) {
		$message = apply_filters( 'tribe_events_community_submission_message', $message, $type );
		$this->messages[] = (object)array('message' => $message, 'type' => $type);
	}

	public function get_invalid_fields() {
		return array_unique($this->invalid_fields);
	}
} 