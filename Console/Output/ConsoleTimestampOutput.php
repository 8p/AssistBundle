<?php

namespace EightPoints\Bundle\AssistBundle\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput,
    Symfony\Component\Console\Formatter\OutputFormatterInterface,
    Symfony\Component\Console\Output\ConsoleOutputInterface;

/**
 * ConsoleTimestampOutput
 * Adds timestamp to each written line in console
 *
 * @package   EightPoints\Bundle\AssistBundle\Console\Output
 *
 * @copyright 8points IT
 * @author    Florian Preusner
 *
 * @version   1.0
 * @since     2013-11
 */
class ConsoleTimestampOutput extends ConsoleOutput implements ConsoleOutputInterface {

    /**
     * @var float $lastTime
     */
    protected $lastTime = 0;

    /**
     * Construct
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-11
     *
     * @param   integer                  $verbosity the verbosity level
     * @param   boolean                  $decorated whether to decorate messages or not (null for auto-guessing)
     * @param   OutputFormatterInterface $formatter output formatter instance
     */
    public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null) {

        $this->lastTime = microtime(true);

        parent::__construct($verbosity = self::VERBOSITY_NORMAL, $decorated = null, $formatter = null);
    }

    /**
     * Write message
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-11
     *
     * @param   string $message
     * @param   @todo  $newline
     *
     * @return  void
     */
    protected function doWrite($message, $newline) {

        $message = $this->addTimeStamp($message);

        parent::doWrite($message, $newline);
    }

    /**
     * Add timestamp/duration to given string
     *
     * @author  Florian Preusner
     * @version 1.0
     * @since   2013-11
     *
     * @param   string $message
     * @return  string $message
     */
    protected function addTimeStamp($message) {

        $now     = microtime(true);
        $diff    = number_format($now - $this->lastTime, 3);
        $prefix  = sprintf('[%s :: %f]', date('H:i:s'), $diff);
        $message = str_pad($prefix, 25) . $message;

        $this->lastTime = microtime(true);

        return $message;
    }
}