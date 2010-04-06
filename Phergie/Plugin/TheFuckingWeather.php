<?php
/**
 * Phergie
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://phergie.org/license
 *
 * @category  Phergie
 * @package   Phergie_Plugin_TheFuckingWeather
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2010 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie_Plugin_TheFuckingWeather
 */

/**
 * Detects and responds to requests for current weather conditions in a
 * particular location using data from a web service.
 *
 * @category Phergie
 * @package  Phergie_Plugin_TheFuckingWeather
 * @author   Phergie Development Team <team@phergie.org>
 * @license  http://phergie.org/license New BSD License
 * @link     http://pear.phergie.org/package/Phergie_Plugin_TheFuckingWeather
 * @uses     Phergie_Plugin_Command pear.phergie.org
 * @uses     Phergie_Plugin_Http pear.phergie.org
 * @see http://thefuckingweather.com
 */

class Phergie_Plugin_TheFuckingWeather extends Phergie_Plugin_Abstract
{
    /**
     * Description of this plugin for the Help plugin
     *
     * @var string
     */
    public $helpDesc = "Detects and responds to requests for current weather
                        conditions in a particular location.";

    /**
     * Description of commands offered by this plugin for the Help plugin
     *
     * @var array
     */
    public $helpCmds = array(
        array(
            "cmd" => "thefuckingweather [location]",
            "desc" => "Detects and responds to requests for current weather
                       conditions in a particular location."),
        array(
            "cmd" => "tfw [location]",
            "desc" => "Alias for thefuckingweather command.")
    );

    /**
     * HTTP plugin
     *
     * @var Phergie_Plugin_Http
     */
    protected $http = null;

    /**
     * Base API URL
     *
     * @var string
     */
    protected $url = 'http://www.thefuckingweather.com/?CELSIUS=yes&zipcode=';

    /**
     * Checks for dependencies.
     *
     * @return void
     */
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
        $this->http = $this->getPluginHandler()->getPlugin('Http');
    }

    /**
     * Returns the weather from the specified location.
     *
     * @param string $location Location term
     *
     * @return void
     * @todo Implement use of URL shortening here
     */
    public function onCommandThefuckingweather($location)
    {
        $target = $this->getEvent()->getNick();
        $out = $this->getWeather($location);
        $this->doPrivmsg($this->getEvent()->getSource(), $target.': '.$out);
    }

    /**
    * Alias for TheFuckingWeather command
    *
    * @return void
    */
    public function onCommandTfw($location)
    {
        $this->onCommandThefuckingweather($location);
    }

    /**
     * Get the necessary content and returns the search result.
     *
     * @param string $location Location term
     *
     * @return string The search result
     * @todo Try to optimize pregs
     */
    protected function getWeather($location) {
        $url = $this->url.urlencode($location);
        $response = $this->http->get($url);
        $content = $response->getContent();

        preg_match_all("#<div><span class=\"small\">(.*?)<\/span><\/div>#im",
                        $content, $matches);
        $location = $matches[1][0];

        if (!empty($location)){
            preg_match_all("#<div class=\"large\" >(.*?)<br \/>#im",
                            $content, $matches);
            $temp_numb = $matches[1][0];

            preg_match_all("#<br \/>(.*?)<\/div><div  id=\"remark\"><br \/>#im",
                            $content, $matches);
            $temp_desc = $matches[1][0];

            preg_match_all("#<div  id=\"remark\"><br \/>\n<span>(.*?)<\/span><\/div>#im",
                            $content, $matches);
            $remark = $matches[1][0];

            $result = "{$location}: {$temp_numb} {$temp_desc} ({$remark})";
            $result = preg_replace('/</',' <', $result);
            $result = strip_tags($result);
            return html_entity_decode($result);
        }else{
            return "I have no idea where is this fucking location!";
        }
    }
}
