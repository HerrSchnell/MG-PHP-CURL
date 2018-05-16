

<?php
 function breakToNewLine($string){ 
  return preg_replace('#<br\s*/?-->#i', "\n", $string);
}

define('MAILGUN_API',"yourApiKey"); //put your mailgun api key here
define('DOMAIN',"yourDomain.org"); //put a domain registered at mailgun here, or the sandbox subdomain
define('FROMADDR', "support@".DOMAIN); // support@yourdomain.org or adjust for your needs

function shootEmail($Receiver, $Title, $HTMLMessage) {

  $mgcurl = curl_init();

  curl_setopt($mgcurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($mgcurl, CURLOPT_USERPWD, 'api:'.MAILGUN_API);
  curl_setopt($mgcurl, CURLOPT_RETURNTRANSFER, 1);
  // If you are not using SSL set this to 0 or false
  curl_setopt($mgcurl, CURLOPT_SSL_VERIFYPEER, 0);

  $PlainText = strip_tags(breakToNewLine($HTMLMessage));

  curl_setopt($mgcurl, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($mgcurl, CURLOPT_URL, 'https://api.mailgun.net/v3/'.DOMAIN.'/messages');
  curl_setopt($mgcurl, CURLOPT_POSTFIELDS,
        array('from' => FROMADDR,
                'to' => $Receiver,
                'subject' => $Title,
                'html' => $HTMLMessage,
                'text' => $PlainText));

  $Response = json_decode(curl_exec($mgcurl),true);


  if(isset($Response['id']))
  {
    curl_close($mgcurl);
    return 1;
  }
}
?>