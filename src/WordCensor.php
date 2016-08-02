<?php

namespace Censor;

class WordCensor
{
    private $censoredWords = array();
    private $replacement = '';
    private $letterSeparatorChars = array();
    private $letterSubstitutions = array();

    /**
     * WordCensor constructor.
     * @param array $censoredWords
     * @param string $replacement
     * @param array $letterSeparatorChars
     * @param array $letterSubstitutions key: letter, value: array of substitutions eg: array('a' => array('@'))
     */
    public function __construct(
        array $censoredWords = array(),
        $replacement = '',
        array $letterSeparatorChars = array(),
        array $letterSubstitutions = array())
    {
        $this->censoredWords = $censoredWords;
        $this->letterSeparatorChars = $letterSeparatorChars;
        $this->replacement = $replacement;
        $this->letterSubstitutions = $letterSubstitutions;
    }

    /**
     * @param string $phrase
     * @return string censored phrase
     */
    public function censor($phrase) 
    {
        $pattern = $this->createCensoredWordsPattern($this->getCensoredWords(), $this->getLetterSeparatorChars(), $this->getLetterSubstitutions());
        
        return (null != $pattern)
            ? preg_replace($pattern, $this->getReplacement(), $phrase)
            : $phrase;
    }

    /**
     * @param array $censoredWords
     * @param array $letterSeparatorChars
     * @param array $letterSubstitutions
     * @return string pattern
     */
    private function createCensoredWordsPattern(array $censoredWords, array $letterSeparatorChars, array $letterSubstitutions)
    {
        foreach ($censoredWords as & $censoredWord) {
            $censoredWord = self::createCensoredWordPattern($censoredWord, $letterSeparatorChars, $letterSubstitutions);
        }
        
        return count($censoredWords)
            ? '/(' . implode( '|', $censoredWords) . ')/i'
            : null;
    }

    /**
     * @param string $censoredWord
     * @param array $letterSeparatorChars
     * @param array $letterSubstitutions
     * @return string censored-word pattern
     */
    private static function createCensoredWordPattern($censoredWord, array $letterSeparatorChars, array $letterSubstitutions)
    {
        $letters = str_split($censoredWord);
        foreach ($letters as & $letter) {
            $letter = self::createLetterPattern($letter, $letterSubstitutions);
        }
        
        // implode array of letter-patterns with letter-separator-chars-pattern and return
        return implode(self::createLetterSeparatorCharsPattern($letterSeparatorChars), $letters);
    }

    /**
     * @param string $letter
     * @param array $letterSubstitutions
     * @return string letter-pattern
     */
    private static function createLetterPattern($letter, array $letterSubstitutions) 
    {
        $letterSubstitutionPattern = WordCensor::createLetterSubstitutionPattern($letter, $letterSubstitutions);
        
        return '['. $letterSubstitutionPattern .']+';
    }

    /**
     * @param string $letter
     * @param array $letterSubstitutions
     * @return string letter-substitution-pattern
     */
    private function createLetterSubstitutionPattern($letter, array $letterSubstitutions)
    {
        // transform to letter substitution array
        $letterSubstitutionArray = array_key_exists($letter, $letterSubstitutions)
            ? array_merge(array($letter), $letterSubstitutions[$letter])
            : array($letter);

        // preg_quote every letter and transform to pattern
        return implode('|', WordCensor::pregQuoteArray($letterSubstitutionArray));
    }
    
    /**
     * @param array $letterSeparatorChars
     * @return string letter-separator-chars-pattern
     */
    private static function createLetterSeparatorCharsPattern(array $letterSeparatorChars)
    {
        return count($letterSeparatorChars)
            ? '[' . implode('|', self::pregQuoteArray($letterSeparatorChars)) . ']*'
            : '';
    }
    
    /**
     * @param array $arrayOfStrings
     * @return array quoted array of strings, ready to use in regular expression
     */
    private static function pregQuoteArray(array $arrayOfStrings)
    {
        return array_map(function ($string) {
            return preg_quote($string, '/');
        }, $arrayOfStrings);
    }

    /**
     * @return array
     */
    public function getCensoredWords()
    {
        return $this->censoredWords;
    }

    /**
     * @return array
     */
    public function getLetterSeparatorChars()
    {
        return $this->letterSeparatorChars;
    }

    /**
     * @return string
     */
    public function getReplacement()
    {
        return $this->replacement;
    }

    /**
     * @return array
     */
    public function getLetterSubstitutions()
    {
        return $this->letterSubstitutions;
    }
}
