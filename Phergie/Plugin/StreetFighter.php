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
* @package   Phergie_Plugin_StreetFighter
* @author    Phergie Development Team <team@phergie.org>
* @copyright 2008-2010 Phergie Development Team (http://phergie.org)
* @license   http://phergie.org/license New BSD License
* @link      http://pear.phergie.org/package/Phergie_Plugin_StreetFighter
*/

/**
* Sends a StreetFighter stroke on a target.
*
* @category Phergie
* @package  Phergie_Plugin_StreetFighter
* @author   Phergie Development Team <team@phergie.org>
* @license  http://phergie.org/license New BSD License
* @link     http://pear.phergie.org/package/Phergie_Plugin_StreetFighter
* @uses     Phergie_Plugin_Command pear.phergie.org
* @todo     Put the strokes on a database like sql lite and
*           get more strokes from another chars
*/

class Phergie_Plugin_StreetFighter extends Phergie_Plugin_Abstract
{

    /**
     * Array with the strokes
     *
     * @var array
     */
    protected $strokes = array(//Abel
                               "Abel's Quick Punches Into Grab",
                               "Abel's Leaping Kick",
                               "Abel's Antiair Grab",
                               "Abel's Command Grab",
                               "Abel's Grab Super",

                               //C. Viper
                               "C.Viper's Electric Jabs",
                               "C.Viper's Ground Explosion",
                               "C.Viper's Flame Kick",
                               "C.Viper's Superjump",
                               "C.Viper's Double Punch",

                               //Ken
                               "Ken's Fireball",
                               "Ken's Uppercut",
                               "Ken's Hurricane Kicks",
                               "Ken's AxeKick",
                               "Ken's Double Shoryuken",

                               //Chun-Li
                               "Chun-Li's Spinning Bird Kick",
                               "Chun-Li's Quick Kicks",
                               "Chun-Li's Flip Kick",
                               "Chun-Li's Fireball",
                               "Chun-Li's Dashing Quick Kicks",

                               //Dhalsim
                               "Dhalsim's Yoga Fire",
                               "Dhalsim's Yoga Flame",
                               "Dhalsim's Vertical Yoga Flame",
                               "Dhalsim's Teleport",
                               "Dhalsim's Foot Drill",
                               "Dhalsim's Head Drill",
                               "Dhalsim's Yoga Inferno",

                               //E.Honda
                               "E.Honda's Headbutt",
                               "E.Honda's Hundred Hand Slap",
                               "E.Honda's Butt Slam",
                               "E.Honda's Ochio Grab",
                               "E.Honda's Multiple Head-butts",

                               //Blanka
                               "Blanka's Horizontal Ball",
                               "Blanka's Vertical Ball",
                               "Blanka's Hop Back Arch Ball",
                               "Blanka's Beast Slide",
                               "Blanka's Hop",
                               "Blanka's Electricity",
                               "Blanka's Super Ball",

                               //Guile
                               "Guile's Sonic Boom",
                               "Guile's Flashkick",
                               "Guile's Backhand",
                               "Guile's Knee Hop",
                               "Guile's Advancing Roundhouse",
                               "Guile's Overhead Punch",
                               "Guile's Super Flashkicks",

                               //Zangief
                               "Zangief's Spinning Pile Driver",
                               "Zangief's Slow Lariat",
                               "Zangief's Fast Lariat",
                               "Zangief's Run and Grab",
                               "Zangief's Glowing Glove",
                               "Zangief's Final Atomic Buster",

                               //Ryu
                               "Ryu's Fireball",
                               "Ryu's Uppercut",
                               "Ryu's Hurricane Kicks",
                               "Ryu's Overhead Punch",
                               "Ryu's Advancing Punch",
                               "Ryu's Super Hadouken"
    );

    /**
     * Description of this plugin for the Help plugin
     *
     * @var string
     */
    public $helpDesc = "Hit a StreetFighter stroke on target.";

    /**
     * Description of commands offered by this plugin for the Help plugin
     *
     * @var array
     */
    public $helpCmds = array(
        array(
            "cmd" => "hit [target]",
            "desc" => "Hit a random stroke on the target."
        ),
        array(
            "cmd" => "hit [target] [strokeName]",
            "desc" => "Hit the specified stroke on the target."
        )
    );

    /**
     * Checks for dependencies.
     *
     * @return void
     */
    public function onLoad()
    {
        $plugins = $this->getPluginHandler();
        $plugins->getPlugin('Command');
    }

    /**
    * Responds to a message requesting that the bot perform an action to
    * serve the source with stroke of a specific type.
    *
    * @param string $target Target to receive the stroke
    * @param string $type Type of stroke
    * @return void
    */
    public function onCommandHit($target, $type='')
    {
        $target = $this->_resolveTarget($target);
        $stroke = $this->_getSentence($type);

        if ($stroke) {
            $text = 'throws a';
            if (preg_match('/^[aeoiu]/i', $stroke)) {
                $text .= 'n';
            }
            $text .= ' ' . $stroke . ' at ' . $target . '.';
            $this->doAction($this->getEvent()->getSource(), $text);
        }
    }

    /**
    * Get a random stroke from the specified type
    *
    * @param string $type Type of stroke
    *
    * @return string Stroke
    */
    protected function _getSentence($type='')
    {
        if ($type != ''){
            $strokes = array();
            foreach ($this->strokes as $s){
                if(preg_match("/{$type}/i", $s)){
                   $strokes[] = $s;
                }
            }
            return $strokes[array_rand($strokes)];
        }else{
            return $this->strokes[array_rand($this->strokes)];
        }
    }

    /**
    * Resolves a target to the appropriate nick or pronoun and returns the
    * result.
    *
    * @param string $target Original specified target
    * @return string Resolved target
    */
    protected function _resolveTarget($target)
    {
        $target = trim($target);
        switch ($target) {
            case 'me':
                $target = $this->getEvent()->getNick();
                break;
            case 'you':
            case $this->_config['connections'][0]['nick']:
                $gender = $this->getConfig('gender');
                if (!$gender || $gender == 'F') {
                    $target = 'herself';
                } else {
                    $target = 'himself';
                }
                break;
        }
        return $target;
    }
}
