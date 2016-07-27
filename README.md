# Word Censor
             
## Usage:

    // required
    $censoredWords = array('raw', 'text');
    $replacement = '[censored]';
    
    // optional 
    $letterSeparatorChars = array('_', '.');
    $letterSubstitutions = array(
        'a' => array('@')
    );

    $wordCensor = new $wordCensor(
        $censoredWords,
        $replacement,
        $letterSeparatorChars,  // optional
        $letterSubstitutions    // optional
    );
    
    $phrase = 'Some raw unceosored text';
    $result = $wordCensor->censor($phrase);
