<?php

$email = NULL;
if (isset($_POST['assertion'])) {
	$persona = new Persona();
	$result = $persona->verifyAssertion($_POST['assertion']);
	if ($result->status === 'okay') {
		$email = $result->email;
		error_log("email set as " . $email);
	}
}

function valid($email) {
	error_log("checking " . $email);
	if (empty($email)) { return false; }
	//error_log("email: " . $email);
	system("grep -q $email ../.emails.txt", $retval);
	//error_log("foo:" . $retval);
	return $retval === 0;
}

class Persona
{
    /**
     * Scheme, hostname and port
     */
    protected $audience;

    /**
     * Constructs a new Persona (optionally specifying the audience)
     */
    public function __construct($audience = NULL)
    {
        $this->audience = $audience ?: $this->guessAudience();
    }

    /**
     * Verify the validity of the assertion received from the user
     *
     * @param string $assertion The assertion as received from the login dialog
     * @return object The response from the Persona online verifier
     */
    public function verifyAssertion($assertion)
    {
        $postdata = 'assertion=' . urlencode($assertion) . '&audience=' . urlencode($this->audience);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://verifier.login.persona.org/verify");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    /**
     * Guesses the audience from the web server configuration
     */
    protected function guessAudience()
    {
        $audience = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $audience .= $_SERVER['SERVER_NAME'] . ':'.$_SERVER['SERVER_PORT'];
        return $audience;
    }
}
