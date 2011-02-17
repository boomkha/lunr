<?php

abstract class L10nProvider
{

    /**
     * Constructor
     * @param String $language POSIX locale definition
     */
    abstract public function __construct($language);

    /**
     * Destructor
     */
    abstract public function __destruct();

    /**
     * Initialization method for setting up the provider
     * @param String $language POSIX locale definition
     * @return void
     */
    abstract private function init($language);

    /**
     * Return a translated string
     * @param String $identifier Identifier for the requested string
     * @return String $string Translated string, identifier by default
     */
    abstract public function lang($identifier);

    /**
     * Return a translated string, with proper singular/plural
     * form
     * @param String $singular Identifier for the singular version of the string
     * @param String $plural Identifier for the plural version of the string
     * @param Integer $amount The amount the translation should be based on
     * @return String $string Translated string, identifier by default
     */
    abstract public function nlang($singular, $plural, $amount);

}

?>