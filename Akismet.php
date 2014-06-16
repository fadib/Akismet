<?php

/*
 * Author:   Fahmi Adib fahmi.adib@gmail.com
 * License:  http://www.gnu.org/copyleft/gpl.html GPL
 * Link:     https://github.com/fadib/Akismet
 */

function Services_Akismet_autoload($className) {
    if (substr($className, 0, 15) != 'Services_Akismet') {
        return false;
    }
    $file = str_replace('_', '/', $className);
    $file = str_replace('Services/', '', $file);
    return include dirname(__FILE__) . "/{$file}.php";
}

spl_autoload_register('Services_Akismet_autoload');


class Services_Akismet {
	
    /**
     * Akismet library version
     */
    const LIB_VERSION = '1.1';

    /**
     * User agent string
     */
    const UA_STRING = 'AkismetPHP';

    /**
     * User agent version
     */
    const UA_VERSION = '1.0.0';
		
	public function __construct() {
		$this->account = new Services_Akismet_Account();
		$this->account->setUserAgent( sprintf( '%s/%s | Akismet/%s', self::UA_STRING, self::UA_VERSION, self::LIB_VERSION ) );
	}
	
	public function verifyKey( $key = null, $blog = null, $ip = null ) {
        if ( null ==== $key || null === $url ) {
            throw new Services_Akismet_HttpException('Both key and blog URL cannot be null');
        }
		
		return $this->account->verifyKey( $key, $blog );
	}

    /**
     * 	Check if comment is spam or not
     *
     * 	@param array $comment data. Required keys:<br />
     *		permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     *
     * 	@return boolean true if message is spam, false otherwise
     */
	public function commentCheck( $comment ) {
		
	}
	
	public function submitSpam( $comment ) {
		
	}
	
	public function submitHam( $comment ) {
		
	}
	
}
