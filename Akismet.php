<?php

/*
 * Author:   Fahmi Adib fahmi.adib@gmail.com
 * License:  http://www.gnu.org/copyleft/gpl.html GPL
 * Link:     https://github.com/fadib/Akismet
 */

function Services_Akismet_autoload( $className ) {
    if ( substr( $className, 0, 16 ) != 'Services_Akismet' ) {
        return false;
    }
    $file = str_replace( '_', '/', $className );
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
    const UA_STRING = 'akismet-php';

    /**
     * User agent version
     */
    const UA_VERSION = '1.0.0';
		
	public function __construct() {
		$this->account = new Services_Akismet_Account();
		$this->account->setUserAgent( sprintf( '%s/%s | Akismet/%s', self::UA_STRING, self::UA_VERSION, self::LIB_VERSION ) );
	}
	
	public function setKey( $key ) {
		$this->account->setKey( $key );
	}
	
	public function setBlogUrl( $blog ) {
		$this->account->setBlogUrl( $blog );
	}
	
	public function isKeyValid() {
		return $this->account->verifyKey();
	}

    /**
     * 	Check if comments is spam or not
     *
     * 	@param array $comment data. Required keys:<br />
     *		permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     *
     * 	@return boolean True if comment is spam, False otherwise
     */
	public function isCommentSpam( $comment ) {
		return $this->account->commentCheck( $comment );
	}
	
    /**
     * Marks comments as spam. Submitting comments that weren't marked as spam but should have been.
     *
     * @param array $comment data. Required keys:<br />
     *      permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     *
     * @return void
     */
	public function submitSpam( $comment ) {
		return $this->account->submitSpam( $comment );
	}
	
    /**
     * This call is intended for the submission of false positives,
	 * items that were incorrectly classified as spam by Akismet.
     *
     * @param array $comment data. Required keys:<br />
     *      permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     *
     * @return void
     */
	public function submitHam( $comment ) {
		return $this->account->submitHam( $comment );
	}
	
	/**
	 * Deprecated
	 */
	public function verifyKey() { return $this->isKeyValid(); }
	public function commentCheck( $comment ) { return $this->isCommentSpam( $comment ); }	
	
}
