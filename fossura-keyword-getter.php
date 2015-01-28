<?php
	function fossura_get_keywords( $title, $document ) {
		
		if ( is_null( get_option('fossura_tags_mode') ) ) {
			$mode = 'classic'; 
		} else {
			$mode = get_option( 'fossura_tags_mode' );	
		}
		
		$cleandoc = strip_tags($document);
		$api_values = fossura_get_soap_data( 'tag-miner-1.0-b26741ca-2583-4dbc-a774-a1fcc4d178aa', 'tag-miner-1.0', $title, $cleandoc, 4, $mode );			
		$keywords_to_return = array();
		$keywords = $api_values -> return -> keywords;
		
		if ($keywords != null) {
			foreach ($keywords as $keyword) {
				array_push($keywords_to_return, $keyword->keyword);
			}
		}
		return $keywords_to_return;
	}

	function fossura_get_soap_data( $apiKey, $username, $header, $document, $analysisRequired, $mode ) {
		$client = new SoapClient( "http://144.76.218.10/FossuraAPI?wsdl" );
		$params = array(
			"apiKey" => $apiKey,
			"username" => $username,
			"document" => array(
				"title" => $header,
				"text" => $document,
			),
			"parameters" => array (
				"mode" => $mode
			),
			"analysis" => $analysisRequired
		);
		return $client->__soapCall( "keywordQuery", array( $params ) );
	}
?>