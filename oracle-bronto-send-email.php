<?php
date_default_timezone_set('Europe/London');
$client = new SoapClient('http://api.bronto.com/v4?wsdl', array(
    'trace' => 1,
    'features' => SOAP_SINGLE_ELEMENT_ARRAYS
));
try {
    // Add API token here
    $token = "YOUR_GOES_HERE";
    print "logging in\n";
    // The session will end if there is no transaction for 20 minutes.
    $sessionId      = $client->login(array(
        'apiToken' => $token
    ))->return;
    $session_header = new SoapHeader("http://api.bronto.com/v4", 'sessionHeader', array(
        'sessionId' => $sessionId
    ));
    $client->__setSoapHeaders(array(
        $session_header
    ));
    // Make delivery start timestamp
    
    // set up a filter to read contacts and match on either of two email addresses
    $filter = array(
        'type' => 'OR',
        'email' => array(
            array(
                'operator' => 'EqualTo',
                'value' => 'john.doe@example.com'
            )
        )
    );
    
    print "reading contacts with equalto filter\n";
    $contacts = $client->readContacts(array(
        'pageNumber' => 1,
        'includeLists' => false,
        'filter' => $filter
    ))->return;
    
    // print matching contact email addresses
    foreach ($contacts as $contact) {
        print_r("Email id==>" . $contact->id . "\n");
    }
    
    $now                     = date('c');
    $deliveryRecipientObject = array(
        'type' => 'contact',
        'id' => 'CONTACT_EMAIL_ID'
    );
    // Create an array of delivery parameters including the content
    // which will be displayed by the loop tags added in the example
    // message.
    $delivery                = array();
    $delivery['start']       = $now;
    $delivery['messageId']   = 'TEMPLATE_MESSAGE_ID';
    $delivery['fromName']    = 'API Robot';
    $delivery['fromEmail']   = 'api_test@example.com';
    $delivery['recipients']  = array(
        $deliveryRecipientObject
    );
    // Notice below that when you reference the name of the loop tag via the API,
    // be sure to leave off the "%%# _#%%" portion of the tag. You will build
    // an array using individual API message tags which are named
    // as follows: basename_number. For example, name => item_1, rather
    // than name => %%#item_#%%.
    $delivery['fields'][]    = array(
        'name' => 'subject',
        'type' => 'html',
        'content' => 'A cool subject'
    );
    $delivery['fields'][]    = array(
        'name' => 'first_name',
        'type' => 'html',
        'content' => '<strong>Panayiotis</strong>'
    );
    $deliveries[]            = $delivery;
    
    $parameters = array(
        'deliveries' => $deliveries
    );
    print_r($parameters);
    $res = $client->addDeliveries($parameters)->return;
    print_r($res);
    if ($res->errors) {
        print "There was a problem scheduling your delivery:\n";
        print $res->results[$res->errors[0]]->errorString . "\n";
        
    } else {
        
        print "Delivery has been scheduled.  Id: " . $res->results[0]->id . "\n";
    }
}
catch (Exception $e) {
    print "uncaught exception\n";
    print_r($e);
}
?>
