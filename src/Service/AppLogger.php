<?php

namespace App\Service;

use Closure;
use Logger;
use think\facade\Log;

/**
 * Class AppLogger
 * @package App\Service
 * @method info($message)
 * @method debug($message)
 * @method error($message)
 */
class AppLogger
{
    const TYPE_LOG4PHP = 'log4php';
    const TYPE_THINK_LOG = 'think-log';

    private $logger;

    private $logBefore = [];

    private $is_static = false;

    public function __construct($type = self::TYPE_LOG4PHP)
    {
        if ($type == self::TYPE_LOG4PHP) {
            $this->logger = Logger::getLogger("Log");
        } elseif ($type == self::TYPE_THINK_LOG) {
            $this->logger = Log::class;
            $this->is_static = true;
            Log::init([
                'default' => 'file',
                'channels' => [
                    'file' => [
                        'type' => 'file',
                        'path' => './logs/',
                    ],
                ],
            ]);
            $this->logBefore[] = function ($message){
                return strtoupper($message);
            };
        }
    }

    private function logBeforeHook($message)
    {
        foreach ($this->logBefore as $item) {
            if ($item instanceof Closure){
                $message = call_user_func_array($item,[$message]);
            }
        }
        return $message;
    }


    public function __call($name, $arguments)
    {
        if (in_array($name,['info','debug','error'])){
            $message = $arguments[0];
            $message = $this->logBeforeHook($message);
            if ($this->is_static){
                call_user_func_array($this->logger.'::'.$name,[$message]);
            }else{
                call_user_func_array([$this->logger,$name],[$message]);
            }
        }
    }
}