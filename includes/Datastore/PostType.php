<?php

namespace Codemanas\VczApi\Datastore;

/**
 * Datastore For Post Types
 *
 * @since   3.7.0
 * @author  Deepen Bajracharya
 */
class PostType {

	/**
	 * Post Type Flag
	 *
	 * @var string
	 */
	private static $post_type = 'zoom-meetings';

	/**
	 * Get Zoom Meeting posts
	 *
	 * @param $args
	 *
	 * @return \WP_Query
	 */
	public static function get_posts( $args = false ) {
		$post_arr = array(
			'post_type'      => self::$post_type,
			'posts_per_page' => ! empty( $args['per_page'] ) ? $args['per_page'] : 10,
			'post_status'    => ! empty( $args['status'] ) ? $args['status'] : 'publish',
			'paged'          => ! empty( $args['paged'] ) ? $args['paged'] : 1,
			'order'          => ! empty( $args['order'] ) ? $args['order'] : 'DESC',
		);

		if ( ! empty( $args['orderby'] ) ) {
			$post_arr['orderby'] = $args['orderby'];
		}

		if ( ! empty( $args['meta_key'] ) ) {
			$post_arr['meta_key'] = $args['meta_key'];
		}

		if ( ! empty( $args['author'] ) ) {
			$post_arr['author'] = absint( $args['author'] );
		}

		if ( ! empty( $args['meta_query'] ) ) {
			$post_arr['meta_query'] = array(
				'relation' => ! empty( $args['meta_relation'] ) ? $args['meta_relation'] : 'AND'
			);

			//If meeting type is not defined then pull all zoom list regardless of webinar or meeting only.
			if ( ! empty( $args['meta_query']['meeting_type'] ) ) {
				$meeting_type = array(
					'relation' => 'OR',
					array(
						'key'     => '_vczapi_meeting_type',
						'value'   => $args['meta_query']['meeting_type'] == "webinar" ? 'webinar' : 'meeting',
						'compare' => '='
					)
				);
				array_push( $post_arr['meta_query'], $meeting_type );
			}

			if ( ! empty( $args['meta_query']['meeting_sort'] ) ) {
				$type            = ( $args['meta_query']['meeting_sort'] == "upcoming" ) ? '>=' : '<=';
				$sort_by_meeting = array(
					'key'     => '_meeting_field_start_date_utc',
					'value'   => vczapi_dateConverter( 'now', 'UTC', 'Y-m-d H:i:s', false ),
					'compare' => $type,
					'type'    => 'DATETIME'
				);
				array_push( $post_arr['meta_query'], $sort_by_meeting );
			}

			if ( ! empty( $args['meta_query']['custom'] ) ) {
				array_push( $post_arr['meta_query'], $args['meta_query']['custom'] );
			}
		}

		if ( ! empty( $args['taxonomy'] ) ) {
			$category              = array_map( 'trim', explode( ',', $args['taxonomy'] ) );
			$post_arr['tax_query'] = [
				[
					'taxonomy' => 'zoom-meeting',
					'field'    => 'slug',
					'terms'    => $category,
					'operator' => 'IN'
				]
			];
		}

		if ( ! empty( $args['custom'] ) ) {
			if ( is_array( $args['custom'] ) ) {
				foreach ( $args['custom'] as $k => $extra ) {
					$post_arr[ $k ] = $extra;
				}
			}
		}

		$query  = apply_filters( 'vczapi_get_posts_query_args', $post_arr );
		$result = new \WP_Query( $query );

		return $result;
	}

	/**
	 * Get a Meeting Object
	 *
	 * @param $meeting_id
	 *
	 * @return bool
	 */
	public static function get_post_by_meeting_id( $meeting_id ) {
		$args = array(
			'post_type'   => self::$post_type,
			'post_status' => 'publish',
			'meta_query'  => array(
				array(
					'key'     => '_meeting_zoom_meeting_id',
					'value'   => $meeting_id,
					'compare' => '='
				)
			)
		);

		$result = new \WP_Query( $args );

		return $result->have_posts();
	}

	/**
	 * Convert given meeting time ot UTC or any user defined value
	 *
	 * @param $start_date
	 * @param $meeting_timezone
	 * @param string $preferred_timezone
	 * @param string $format
	 *
	 * @return string
	 */
	public static function convertToUTC( $start_date, $meeting_timezone, $preferred_timezone = 'UTC', $format = 'Y-m-d H:i:s' ) {
		try {
			$meeting   = new \DateTime( $start_date, new \DateTimeZone( $meeting_timezone ) );
			$converted = $meeting->setTimezone( new \DateTimeZone( $preferred_timezone ) );
			$result    = $converted->format( $format );
		} catch ( \Exception $e ) {
			$result = $e->getMessage();
		}

		return $result;
	}

	/**
	 * Save Meta Field Values
	 *
	 * @param $meeting_info
	 * @param $post_id
	 */
	public static function save_meta_fields( $meeting_info, $post_id ) {
		$meeting_type = ! empty( $meeting_info['meeting_type'] ) && $meeting_info['meeting_type'] === 2 ? 'webinar' : 'meeting';

		//Update Post Meta Values
		update_post_meta( $post_id, '_meeting_fields', $meeting_info );
		update_post_meta( $post_id, '_vczapi_meeting_type', $meeting_type );

		//Check if start date exists
		if ( ! empty( $meeting_info ) && ! empty( $meeting_info['start_date'] ) ) {
			//converted saved time from the timezone provided for meeting to UTC timezone so meetings can be better queried
			$converted = self::convertToUTC( $meeting_info['start_date'], $meeting_info['timezone'] );
			update_post_meta( $post_id, '_meeting_field_start_date_utc', $converted );
		}
	}

	/**
	 * Create Zoom Meeting or Webinar
	 *
	 * @param $meeting_info
	 * @param $post
	 */
	public static function create_zoom_meeting( $meeting_info, $post ) {
		$meeting_type = absint( $meeting_info['meeting_type'] );
		if ( ! empty( $meeting_type ) && $meeting_type === 2 ) {
			$data = self::prepare_webinar_data( $meeting_info, $post );
			//Create Zoom Webinar
			$created = json_decode( zoom_conference()->createAWebinar( $meeting_info['userId'], $data ) );
		} else {
			$data = self::prepare_meeting_data( $meeting_info, $post );
			//Create Zoom Meeting
			$created = json_decode( zoom_conference()->createAMeeting( $data ) );
		}

		//IF IS A POST TYPE THEN SAVE VALUES otherwise neglate
		if ( ! empty( $post ) ) {
			if ( empty( $created->code ) ) {
				update_post_meta( $post->ID, '_meeting_zoom_details', $created );
				update_post_meta( $post->ID, '_meeting_zoom_join_url', $created->join_url );
				update_post_meta( $post->ID, '_meeting_zoom_start_url', $created->start_url );
				update_post_meta( $post->ID, '_meeting_zoom_meeting_id', $created->id );
			} else {
				//Store Error Message
				update_post_meta( $post->ID, '_meeting_zoom_details', $created );
			}
		}
	}

	/**
	 * Update Zoom Meeting Here
	 *
	 * @param $meeting_info
	 * @param $post
	 * @param $meeting_id
	 */
	public static function update_zoom_meeting( $meeting_info, $post, $meeting_id ) {
		$meeting_type = absint( $meeting_info['meeting_type'] );
		if ( ! empty( $meeting_type ) && $meeting_type === 2 ) {
			$data    = self::prepare_webinar_data( $meeting_info, $post );
			$updated = json_decode( zoom_conference()->updateWebinar( $meeting_id, $data ) );
		} else {
			$data    = self::prepare_meeting_data( $meeting_info, $post, $meeting_id );
			$updated = json_decode( zoom_conference()->updateMeetingInfo( $data ) );
		}

		//If is a POST TYPE
		if ( ! empty( $post ) ) {
			//Update Now
			if ( empty( $updated->code ) ) {
				//If is webinar
				if ( ! empty( $meeting_type ) && $meeting_type === 2 ) {
					$result = json_decode( zoom_conference()->getWebinarInfo( $meeting_id ) );
				} else {
					$result = json_decode( zoom_conference()->getMeetingInfo( $meeting_id ) );
				}

				if ( ! empty( $result ) ) {
					update_post_meta( $post->ID, '_meeting_zoom_details', $result );
					update_post_meta( $post->ID, '_meeting_zoom_join_url', $result->join_url );
					update_post_meta( $post->ID, '_meeting_zoom_start_url', $result->start_url );
					update_post_meta( $post->ID, '_meeting_zoom_meeting_id', $result->id );
				}
			} else {
				//Store Error Message
				update_post_meta( $post->ID, '_meeting_zoom_details', $updated );
			}
		}
	}

	/**
	 * Prepare webinar data before creating webinar
	 *
	 * @param $meeting_info
	 * @param $post
	 *
	 * @return mixed|void
	 */
	protected static function prepare_webinar_data( $meeting_info, $post ) {
		$start_time        = gmdate( "Y-m-d\TH:i:s", strtotime( $meeting_info['start_date'] ) );
		$alternative_hosts = $meeting_info['alternative_host_ids'];
		if ( ! empty( $alternative_hosts ) ) {
			$alternative_host_ids = count( $alternative_hosts ) > 1 ? implode( ",", $alternative_hosts ) : $alternative_hosts[0];
		}

		$result = apply_filters( 'vczapi_webinar_create_data', array(
			'topic'      => ! empty( $post ) ? esc_html( $post->post_title ) : esc_html( $meeting_info['topic'] ),
			'agenda'     => ! empty( $post ) ? esc_html( $post->post_content ) : esc_html( $meeting_info['agenda'] ),
			'start_time' => $start_time,
			'timezone'   => $meeting_info['timezone'],
			'password'   => ! empty( $meeting_info['password'] ) ? $meeting_info['password'] : '',
			'duration'   => ! empty( $meeting_info['duration'] ) ? absint( $meeting_info['duration'] ) : 40,
			'settings'   => array(
				'host_video'             => ! empty( $meeting_info['option_host_video'] ) ? true : false,
				'panelists_video'        => ! empty( $meeting_info['panelists_video'] ) ? true : false,
				'practice_session'       => ! empty( $meeting_info['practice_session'] ) ? true : false,
				'hd_video'               => ! empty( $meeting_info['hd_video'] ) ? true : false,
				'allow_multiple_devices' => ! empty( $meeting_info['allow_multiple_devices'] ) ? true : false,
				'auto_recording'         => ! empty( $meeting_info['option_auto_recording'] ) ? true : "none",
				'alternative_hosts'      => ! empty( $alternative_host_ids ) ? $alternative_host_ids : ''
			)
		) );

		return $result;
	}

	/**
	 * Prepare meeting data before creating the meeting
	 *
	 * @param $meeting_info
	 * @param $post
	 * @param $meeting_id
	 *
	 * @return mixed|void
	 */
	protected static function prepare_meeting_data( $meeting_info, $post, $meeting_id = false ) {
		$result = apply_filters( 'vczapi_meeting_create_data', array(
			'userId'                    => $meeting_info['userId'],
			'meetingTopic'              => ! empty( $post ) ? esc_html( $post->post_title ) : esc_html( $meeting_info['topic'] ),
			'start_date'                => $meeting_info['start_date'],
			'timezone'                  => $meeting_info['timezone'],
			'duration'                  => $meeting_info['duration'],
			'password'                  => $meeting_info['password'],
			'meeting_authentication'    => $meeting_info['meeting_authentication'],
			'join_before_host'          => $meeting_info['join_before_host'],
			'option_host_video'         => $meeting_info['option_host_video'],
			'option_participants_video' => $meeting_info['option_participants_video'],
			'option_mute_participants'  => $meeting_info['option_mute_participants'],
			'option_auto_recording'     => $meeting_info['option_auto_recording'],
			'alternative_host_ids'      => $meeting_info['alternative_host_ids']
		) );

		if ( ! empty( $meeting_id ) ) {
			$result['meeting_id'] = absint( $meeting_id );
		}

		return $result;
	}
}