<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated') );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'DVWA Security' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'security';

$securityHtml = '';
if( isset( $_POST['seclev_submit'] ) ) {
	// Anti-CSRF
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'security.php' );

	// Force security level to 'low' only
	$securityLevel = 'low';

	dvwaSecurityLevelSet( $securityLevel );
	dvwaMessagePush( "Security level set to {$securityLevel}" );
	dvwa_start_session();
	dvwaPageReload();
}

$securityOptionsHtml = '';
$securityLevelHtml   = '';
// Only allow 'low' security level
$securityLevel = 'low';
$selected = ' selected="selected"';
$securityLevelHtml = "<p>Security level is currently: <em>$securityLevel</em> (locked to low only).<p>";
$securityOptionsHtml .= "<option value=\"{$securityLevel}\"{$selected}>" . ucfirst($securityLevel) . "</option>";

// Anti-CSRF
generateSessionToken();

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>DVWA Security <img src=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/images/lock.png\" /></h1>
	<br />

	<h2>Security Level</h2>

	{$securityHtml}

	<form action=\"#\" method=\"POST\">
		{$securityLevelHtml}
		<p><strong>Security level is locked to LOW only.</strong> The security level changes the vulnerability level of DVWA:</p>
		<ol>
			<li> Low - This security level is completely vulnerable and <em>has no security measures at all</em>. It's use is to be as an example of how web application vulnerabilities manifest through bad coding practices and to serve as a platform to teach or learn basic exploitation techniques.</li>
		</ol>
		<select name=\"security\">
			{$securityOptionsHtml}
		</select>
		<input type=\"submit\" value=\"Submit\" name=\"seclev_submit\">
		" . tokenField() . "
		<p><em>Note: Security level is locked to LOW. Other levels are not available.</em></p>
	</form>
	
	<br>
	<br>
	<h2>Additional Tools</h2>
	<ul>
		<li><a href=\"" . DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/bac/log_viewer.php\">View Broken Access Control Logs</a> - View access logs for the Broken Access Control vulnerability</li>
	</ul>
</div>";

dvwaHtmlEcho( $page );

?>
