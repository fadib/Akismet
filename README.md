Akismet
===============

Akismet wrapper for PHP 5.2+

Usage
=====

Include the library file

	require_once '/path/to/Akismet.php';
	
Initalizing class and set required API key and home/blog URL

    $akismet = new Services_Akismet();
	$akismet->setKey("XXXXXX");
	$akismet->setBlogUrl("http://XXXXXX");

Verifying API key

    $akismet->verifyKey();

Checking if comment is spam

    $akismet->commentCheck( array( 
        'permalink' => 'The permanent location of the entry the comment was submitted to',
        'comment_type' => 'May be blank, comment, trackback, pingback, or a made up value like "registration"',
        'comment_author' => 'Name submitted with the comment',
        'comment_author_email' => 'Email address submitted with the comment',
        'comment_author_url' => 'URL submitted with comment',
        'comment_content' => 'The content that was submitted'
    ) );

Above function returns `true`, if comment is spam, `false` otherwise. 
`$akismet->submitSpam()` and `$akismet->submitHam()` works same way.
