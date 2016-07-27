<?php

use \Censor\WordCensor;

class WordCensorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider censorTextsData
     *
     * @param $censoredWords
     * @param $specialChars
     * @param $replacement
     * @param $rawText
     * @param $expectedText
     * @param $letterSubstitutions
     */
    public function shouldCensorWords($censoredWords, $specialChars, $replacement, $rawText, $expectedText, $letterSubstitutions) {

        $wordCensor = new WordCensor($censoredWords, $replacement, $specialChars, $letterSubstitutions);
        $censoredText = $wordCensor->censor($rawText);
        $this->assertEquals($expectedText, $censoredText);
    }
    
    public function censorTextsData() {
        return array(
            array(
                // #0: lowercase test
                'censoredWords' => array('text', 'abc', 'ok'),
                'specialChars' => array(),
                'replacement' => '[censored]',
                'rawText' => 'lowercase phrase with abc and ok and abc again',
                'expectedText' => 'lowercase phrase with [censored] and [censored] and [censored] again',
                'letterSubstitutions' => array()
            ),
            array(
                // #1: censored words contain reserved chars
                'censoredWords' => array('/', '\\', '$', '\'', '"', '|'),
                'specialChars' => array(),
                'replacement' => '[censored]',
                'rawText' => 'slash / backslash \ dollar sign $ quote \' double quote " pipe |',
                'expectedText' => 'slash [censored] backslash [censored] dollar sign [censored] quote [censored] double quote [censored] pipe [censored]',
                'letterSubstitutions' => array()
            ),
            array(
                // #2: lowercase/uppercase
                'censoredWords' => array('text', 'abc', 'ok'),
                'specialChars' => array(),
                'replacement' => '[censored]',
                'rawText' => 'lowercase/uppercase phrase with AbC and oK and aBc again',
                'expectedText' => 'lowercase/uppercase phrase with [censored] and [censored] and [censored] again',
                'letterSubstitutions' => array()
            ),
            array(
                // #3: in string
                'censoredWords' => array('text', 'abc', 'ok'),
                'specialChars' => array(),
                'replacement' => '[censored]',
                'rawText' => 'thisisabcandokandtext1234',
                'expectedText' => 'thisis[censored]and[censored]and[censored]1234',
                'letterSubstitutions' => array()
            ),
            array(
                // #4: multiline
                'censoredWords' => array('text', 'abc', 'ok'),
                'specialChars' => array(),
                'replacement' => '[censored]',
                'rawText' => "lowercase \r\n phrase with \r\nabc \tand \nok and abc again",
                'expectedText' => "lowercase \r\n phrase with \r\n[censored] \tand \n[censored] and [censored] again",
                'letterSubstitutions' => array()
            ),
            array(
                // #5: special chars inside censored words
                'censoredWords' => array('text', 'abc', 'ok'),
                'specialChars' => array('_', '@', ' '),
                'replacement' => '[censored]',
                'rawText' => 'sample @t@ex_t with _a@b__c__ and @@@o@@@k__ and @_@_a_b_c_ or a b c !',
                'expectedText' => 'sample @[censored] with _[censored]__ and @@@[censored]__ and @_@_[censored]_ or [censored] !',
                'letterSubstitutions' => array()
            ),
            array(
                // #6: letter repeating
                'censoredWords' => array('text', 'abc', 'okay'),
                'specialChars' => array(),
                'replacement' => '[censored]',
                'rawText' => 'this is ttteexttt and ohaabbc! and wowokkayyolright',
                'expectedText' => 'this is [censored] and oh[censored]! and wow[censored]olright',
                'letterSubstitutions' => array()
            ),
            array(
                // #6: letter repeating, uppercase, multiline
                'censoredWords' => array('text', 'aBc', 'okay', 'wo|la'),
                'specialChars' => array(),
                'replacement' => '[censored]',
                'rawText' => "this is \r\nttteEXttt and ohaabbC! \t\r\n\nand wowokkayyolright tuWWoO||lLaXXX",
                'expectedText' => "this is \r\n[censored] and oh[censored]! \t\r\n\nand wow[censored]olright tu[censored]XXX",
                'letterSubstitutions' => array()
            ),
            array(
                // #7: letter repeating, uppercase, multiline, special chars
                'censoredWords' => array('text', 'aBc', 'okay', 'wo|la'),
                'specialChars' => array('.', '_', ' '),
                'replacement' => '[censored]',
                'rawText' => "this is text and oh@8b.c! \t\r\n\nand wowokkayyolright tuWWoO||lLaXXX",
                'expectedText' => "this is [censored] and oh[censored]! \t\r\n\nand wow[censored]olright tu[censored]XXX",
                'letterSubstitutions' => array(
                    'a' => array('@', '2'),
                    'b' => array('8', '6'),
                    'B' => array('8')
                )
            )
        );
    }
}
