<?php

defined('ABSPATH') || exit;

class Configuration
{

    /**
     * Language of the plugin
     *
     * @var string
     */
    private $language;

    function __construct()
    {
        $this->detectChangeLanguage();
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set language
     *
     * @return void
     */
    function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Set language in the database
     *
     * @param string $language
     * @return void
     */
    public function setLanguageDatabase($language)
    {
        update_option('wep_scraping_plugin_language', $language);
    }

    /**
     * Detects the language change
     *
     * @return void
     */
    function detectChangeLanguage()
    {
        if (isset($_POST['language'])) {
            $this->setLanguageDatabase($_POST['language']);
            $this->setLanguage($_POST['language']);
        } else {
            $this->setLanguage(get_option('wep_scraping_plugin_language'));
        }
    }
}
