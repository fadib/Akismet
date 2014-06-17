<?php

class Services_Akismet_Account {
	
    /**
     * Base URL of Akismet API
     */
    const AKISMET_URL = 'rest.akismet.com';

    /**
     * Akismet API version to use
     */
    const AKISMET_API_VERSION = '1.1';

    /**
     * API method to verify key
     */
    const PATH_VERIFY = 'verify-key';

    /**
     * API method to check if message is spam
     */
    const PATH_CHECK = 'comment-check';

    /**
     * API method to mark message as spam
     */
    const PATH_SPAM = 'submit-spam';

    /**
     * API method to mark message as ham (not-spam)
     */
    const PATH_HAM = 'submit-ham';
	
    /*
     * Possible API return values
     */
    const RETURN_TRUE = 'true';
    const RETURN_FALSE = 'false';
    const RETURN_INVALID = 'invalid';
    const RETURN_VALID = 'valid';
    const RETURN_THANKS = 'Thanks for making the web a better place.';
	
	private $userAgent, $key, $blog, $url, $verify_url;
	private $error;
	
	public function __construct() {
        if ( !function_exists('curl_init') ) {
            throw new Services_Akismet_HttpException('This library requires cURL extension');
        }
		
		$this->verify_url = sprintf( "http://%s/%s/", self::AKISMET_URL, self::AKISMET_API_VERSION );
	}
	
	public function setKey( $key ) {
		$this->key = $key;
		$this->url = sprintf( "http://%s.%s/%s/", $this->key, self::AKISMET_URL, self::AKISMET_API_VERSION );
	}
	
	public function setBlogUrl( $blog ) {
		$this->blog = $blog;
	}
	
	public function setUserAgent( $ua ) {
		$this->userAgent = $ua;
	}
	
	public function verifyKey() {
        if ( null === $this->key || null === $this->blog ) {
            throw new Services_Akismet_HttpException('Both key and blog URL cannot be null');
        }
		
		$verifying = $this->query( self::PATH_VERIFY, array( 'key' => $this->key, 'blog' => $this->blog ), self::RETURN_VALID );
		if ( true === $verifying ) {
			return true;
		}
		
		return false;
	}
	
	public function commentCheck( $comment ) {
		return $this->query( self::PATH_CHECK, $comment, self::RETURN_TRUE );
	}
	
	public function submitSpam( $comment ) {
		return $this->query( self::PATH_SPAM, $comment, self::RETURN_THANKS );
	}
	
	public function submitHam( $comment ) {
		return $this->query( self::PATH_HAM, $comment, self::RETURN_THANKS );
	}
	
	private function query( $path, $data, $return ) {
		$_url = self::PATH_VERIFY === $path ? $this->verify_url : $this->url;
		
        if ( self::PATH_VERIFY !== $path ) {
			$data['blog'] = $this->blog;
			
            if ( !array_key_exists( 'user_ip', $data ) ) {
                $data['user_ip'] = $_SERVER['REMOTE_ADDR'];
            }
            if ( !array_key_exists( 'user_agent', $data ) ) {
                $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            }
            if ( !array_key_exists( 'referrer', $data ) ) {
                $data['referrer'] = $_SERVER['HTTP_REFERER'];
            }
        }
		
        $_http = new Services_Akismet_TinyHttp(
            sprintf( "%s", $_url ),
            array(
                "curlopts" => array(
                    CURLOPT_USERAGENT => $this->userAgent,
                    CURLOPT_HTTPHEADER => array('Accept-Charset: utf-8'),
		            CURLOPT_POST => true,
                ),
			)
        );
		
		$_http_return = $_http->post( 
			$path, 
			array('Content-Type' => 'application/x-www-form-urlencoded'), 
			http_build_query( $data ) 
		);
		
        if ( trim( end( $_http_return ) ) == $return ) {
            return true;
        } else {
            foreach ( $_http_return[1] as $header ) {
                if ( stripos( $header, 'X-akismet-debug-help' ) === 0 ) {
                    $this->error = trim( $header );
                }
            }
            return false;
        }
	}
	
	public function getError() {
		return $this->error;
	}
	
}