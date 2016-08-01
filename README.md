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

    $wordCensor = new WordCensor(
        $censoredWords,
        $replacement,
        $letterSeparatorChars,  // optional
        $letterSubstitutions    // optional
    );
    
    $phrase = 'Some raw text';
    $result = $wordCensor->censor($phrase);
    
    // output: 'Some [censored] [censored]'
