<?php


/**
 * Created by PhpStorm.
 * User: Stephen
 * Date: 05/13/2020
 * Time: 12:55 PM
 */

namespace App\Helpers;

use App\Conf\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CustomLogger
{
    /**
     * Mojox log variables
     */

    public $tipsyDebugLogger;
    public $tipsyInfoLogger;
    public $tipsyErrorLogger;
    public $tipsyFatalLogger;


    public function __construct()
    {
        /**
         * initialising the different log functions for Tipsy
         */
        $this->tipsyDebugLogger = new Logger(Config::TIPSY_APP_NAME);
        $this->tipsyInfoLogger = new Logger(Config::TIPSY_APP_NAME);
        $this->tipsyFatalLogger = new Logger(Config::TIPSY_APP_NAME);
        $this->tipsyErrorLogger = new Logger(Config::TIPSY_APP_NAME);

        $this->tipsyDebugLogger->pushHandler(new StreamHandler(Config::TIPSY_DEBUG, Logger::DEBUG));
        $this->tipsyInfoLogger->pushHandler(new StreamHandler(Config::TIPSY_INFO, Logger::INFO));
        $this->tipsyFatalLogger->pushHandler(new StreamHandler(Config::TIPSY_ERROR, Logger::ERROR));
        $this->tipsyErrorLogger->pushHandler(new StreamHandler(Config::TIPSY_FATAL, Logger::EMERGENCY));
    }
}
