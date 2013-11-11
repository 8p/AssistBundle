<?php

namespace EightPoints\Bundle\AssistBundle\Extension;

use Twig_Function_Method;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\DateTime;




class TwigExtension extends \Twig_Extension {

    protected $translator;

    /**
     * Constructor method
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(Translator $translator = null) {

        $this->translator = $translator;
    }

    /**
     * Get filters
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @return array
     */
    public function getFilters() {

        return array(
            'md5'           => new \Twig_Filter_Method($this, 'md5'),
            'substr'        => new \Twig_Filter_Method($this, 'substr'),
            'round'         => new \Twig_Filter_Method($this, 'round'),
            'strtolower'    => new \Twig_Filter_Method($this, 'strtolower'),
            'locale'        => new \Twig_Filter_Method($this, 'locale'),
            'url'           => new \Twig_Filter_Method($this, 'url'),
            'only'          => new \Twig_Filter_Method($this, 'only'),
            'explode'       => new \Twig_Filter_Method($this, 'explode'),
            'time_in_words' => new \Twig_Filter_Method($this, 'timeInWords')
        );
    } // end: getFilters()

    /**
     * Get name if extension
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @return string
     */
    public function getName() {

        return 'assist';
    } // end: getName()

    /**
     * php: strtolower
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param  string $value
     * @return string
     */
    public function strtolower($value) {

        return strtolower($value);
    } // end: strtolower()

    /**
     * php: md5
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param  string $value
     * @return string
     */
    public function md5($value) {

        return md5($value);
    } // end: md5()

    /**
     * php: round
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param  float $value
     * @return integer
     */
    public function round($value) {

        return round($value);
    } // end: round()

    /**
     * Substr
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param  string         $string
     * @param  integer|string $start
     * @param  integer|string $max
     * @param  string         $ending
     * @return string
     */
    public function substr($string, $start, $max = null, $ending = false) {

        if(is_string($start)) :
            
            $start = strpos($string, $start);
        endif;
        
        if(is_string($max)) :
            
            $max = strpos($string, $max);
        endif;
        
        
        // Return original string if length is higher than strlen
        if($max && $max >= strlen($string)) :

            return $string;
        endif;

        if($ending) :

            $string  = substr($string, $start, $max - strlen($ending));
            $string .= $ending;
        else :

            if($max) :
            
                $string = substr($string, $start, $max);
            else :
                
                $string = substr($string, $start);
            endif;
        endif;

        return $string;
    } // end: substr()

    /**
     * Prepare route parameters for language switch.
     *
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param $locale
     * @param $params
     * @return array
     */
    public function locale($locale, $params) {

        unset(
            $params['_route'],
            $params['_route_params'],
            $params['_template_streamable']
        );

        foreach($params as $key => $param) :
            
            if(strpos($key, '_template_default_vars') !== false) :
                
                unset($params[$key]);
            endif; 
        endforeach;

        $params['_locale'] = $locale;

        return $params;
    } // end: locale()

    /**
     * Find urls and create hyper links
     *
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param  string  $text
     * @param  boolean $external  open link as target="_blank"
     * @return string  $text
     */
    public function url($text, $external = null) {

        $regex  = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $target = ($external) ? 'target="_blank"' : '';

        // check if there is a url in the text
        if(preg_match($regex, $text, $url)) :

            // make the urls hyper links
            $text = preg_replace($regex, "<a href=\"{$url[0]}\" $target>{$url[0]}</a> ", $text);
        endif;

        return $text;
    } // end: url()
    
    /**
     * Display only given keys ($display) on $input
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param  array $input
     * @param  array $display
     * @return type
     */
    public function only(array $input, array $display) {
        
        $return = array();
        
        foreach($input as $key => $value) :
            
            if(in_array($key, $display)) :
                
                $return[$key] = $value;
            endif;
        endforeach;
        
        return $return;
    } // end: only
    
    /**
     * php: explode
     * 
     * @author Florian Preusner <florian.preusner@8points.de>
     * @param  string $value
     * @param  string $delimiter
     * @return array
     */
    public function explode($value, $delimiter = ',') {

        return explode($delimiter, $value);
    } // end: explode()

    /**
     * Reports the approximate distance in time between two times given in seconds
     * or in a valid ISO string like.
     * For example, if the distance is 47 minutes, it'll return
     * "about 1 hour". See the source for the complete wording list.
     *
     * Integers are interpreted as seconds. So, by example to check the distance of time between
     * a created user an it's last login:
     * {{ user.createdAt|distance_of_time_in_words(user.lastLoginAt) }} returns "less than a minute".
     *
     * Set include_seconds to true if you want more detailed approximations if distance < 1 minute
     *
     * @param $from_time String or DateTime
     * @param $to_time String or DateTime
     * @param bool $include_seconds
     *
     * @return mixed
     */
    public function timeInWords($from_time, $to_time = null, $include_seconds = false) {

        $datetime_transformer  = new DateTimeToStringTransformer(null, null, 'Y-m-d H:i:s');
        $timestamp_transformer = new DateTimeToTimestampTransformer();

        if(is_double($from_time)) {

            $from_time = date('Y-m-d H:i:s', time() - (int) $from_time);
        }

        # Transforming to DateTime
        $from_time = (!($from_time instanceof \DateTime)) ? $datetime_transformer->reverseTransform($from_time) : $from_time;

        $to_time = empty($to_time) ? new \DateTime('now') : $to_time;
        $to_time = (!($to_time instanceof \DateTime)) ? $datetime_transformer->reverseTransform($to_time) : $to_time;

        # Transforming to Timestamp
        $from_time = $timestamp_transformer->transform($from_time);
        $to_time = $timestamp_transformer->transform($to_time);

        $distance_in_minutes = round((abs($to_time - $from_time))/60);
        $distance_in_seconds = round(abs($to_time - $from_time));

        if ($distance_in_minutes <= 1){
            if ($include_seconds){
                if ($distance_in_seconds < 5){
                    return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 5));
                }
                elseif($distance_in_seconds < 10){
                    return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 10));
                }
                elseif($distance_in_seconds < 20){
                    return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 20));
                }
                elseif($distance_in_seconds < 40){
                    return $this->translator->trans('half a minute ago');
                }
                elseif($distance_in_seconds < 60){
                    return $this->translator->trans('less than a minute ago');
                }
                else {
                    return $this->translator->trans('1 minute ago');
                }
            }
            return ($distance_in_minutes===0) ? $this->translator->trans('less than a minute ago', array()) : $this->translator->trans('1 minute ago', array());
        }
        elseif ($distance_in_minutes <= 45){
            return $this->translator->trans('%minutes minutes ago', array('%minutes' => $distance_in_minutes));
        }
        elseif ($distance_in_minutes <= 90){
            return $this->translator->trans('about 1 hour ago');
        }
        elseif ($distance_in_minutes <= 1440){
            return $this->translator->trans('about %hours hours ago', array('%hours' => round($distance_in_minutes/60)));
        }
        elseif ($distance_in_minutes <= 2880){
            return $this->translator->trans('1 day ago');
        }
        else{
            return $this->translator->trans('%days days ago', array('%days' => round($distance_in_minutes/1440)));
        }
    }
} // end: TwigExtension