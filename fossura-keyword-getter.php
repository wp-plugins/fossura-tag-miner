<?php
	function fossura_get_keywords( $title, $text ) {
		
		if ( is_null( get_option('fossura_tags_mode') ) ) {
			$mode = 'classic'; 
		} else {
			$mode = get_option( 'fossura_tags_mode' );	
		}

		if ( is_null( get_option('fossura_tags_number') ) ) {
			$number = '5'; 
		} else {
			$number = get_option( 'fossura_tags_number' );	
		}

		$cleandoc = strip_tags($text);

		$api_key = get_option( 'textcavate_api_key');
		$username = get_option( 'textcavate_username');

		$api_values = fossura_get_soap_data( $api_key, $username, $title, $cleandoc, $mode );			
		
		$keywords_to_return = array();
		$keywords = $api_values -> return -> keywords;
		
		$a = 1;

		if ($keywords != null) {
			foreach ($keywords as $keyword) {
				array_push($keywords_to_return, $keyword->keyword);
			}
		}
		return $keywords_to_return;
		// $test = array($api_values -> return -> error -> resultMessage);
		// return $test;
	}

	function fossura_get_soap_data( $apiKey, $username, $title, $text, $mode ) {
		$number = get_option( 'fossura_tags_number' );
		$includeDates = get_option ( 'fossura_tags_dates' );
		$includePronouns = get_option ( 'fossura_tags_pronouns' );

		if ( 'true' == $includeDates ) {
			$includeDates = '1';
		}
		else {
			$includeDates = '0';
		}

		if ( 'true' == $includePronouns ) {
			$includePronouns = '1';
		}
		else {
			$includePronouns = '0';
		}



		$client = new SoapClient( "http://api.textcavate.com/textCavateAPI?wsdl" );
		$params = array(
			"apiKey" => $apiKey,
			"username" => $username,
			"document" => array(
				"title" => $title,
				"text" => $text,
			),
			"parameters" => array(
				"mode" => $mode,
				"numKeywords" => $number,
   				"includeDates" => $includeDates,
				"includePronouns" => $includePronouns
			),
		);
		return $client->__soapCall( "keywordQuery", array( $params ) );
	}
?>