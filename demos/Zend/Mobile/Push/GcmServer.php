    <?php
    require_once 'Zend/Mobile/Push/Gcm.php';
    require_once 'Zend/Mobile/Push/Message/Gcm.php';

    $message = new Zend_Mobile_Push_Message_Gcm();
    $message->setId(time());
    $message->addToken('ABCDEF0123456789');
    $message->setData(array(
        'foo' => 'bar',
        'bar' => 'foo',
    ));
    
    $gcm = new Zend_Mobile_Push_Gcm();
    $gcm->setApiKey('MYAPIKEY');

    $response = false;

    try {
        $response = $gcm->send($message);
    } catch (Zend_Mobile_Push_Exception $e) {
        // all other exceptions only require action to be sent or implementation of exponential backoff.
        die($e->getMessage());
    }

    // handle all errors and registration_id's
    foreach ($response->getResults() as $k => $v) {
        if ($v['registration_id']) {
            printf("%s has a new registration id of: %s\r\n", $k, $v['registration_id']);
        }
        if ($v['error']) {
            printf("%s had an error of: %s\r\n", $k, $v['error']);
        }
        if ($v['message_id']) {
            printf("%s was successfully sent the message, message id is: %s", $k, $v['message_id']);
        }
    }
