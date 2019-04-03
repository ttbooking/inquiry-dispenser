<?php

namespace TTBooking\InquiryDispenser\Concerns;

use Illuminate\Contracts\Events\Dispatcher;

trait HasEvents
{
    /** @var array $events */
    protected $events = [];

    /** @var array $observables */
    protected static $observables = [];

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * @param object|string $class
     * @return void
     */
    public static function observe($class)
    {
        $className = is_string($class) ? $class : get_class($class);

        foreach (static::getObservableEvents() as $event) {
            if (method_exists($class, $event)) {
                static::registerFactorEvent($event, $className.'@'.$event);
            }
        }
    }

    /**
     * @return array
     */
    public static function getObservableEvents()
    {
        return array_merge(
            ['activating', 'activated', 'deactivating', 'deactivated'],
            static::$observables
        );
    }

    /**
     * @param array $observables
     * @return $this
     */
    public static function setObservableEvents(array $observables)
    {
        static::$observables = $observables;
    }

    /**
     * @param array|mixed $observables
     * @return void
     */
    public static function addObservableEvents($observables)
    {
        static::$observables = array_unique(array_merge(
            static::$observables, is_array($observables) ? $observables : func_get_args()
        ));
    }

    /**
     * @param array|mixed $observables
     * @return void
     */
    public static function removeObservableEvents($observables)
    {
        static::$observables = array_diff(
            static::$observables, is_array($observables) ? $observables : func_get_args()
        );
    }

    /**
     * @param string $event
     * @param \Closure|string $callback
     * @return void
     */
    protected static function registerFactorEvent($event, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;

            static::$dispatcher->listen("dispenser.factor.{$event}: {$name}", $callback);
        }
    }

    /**
     * @param string $event
     * @param bool $halt
     * @return mixed
     */
    protected function fireFactorEvent($event, $halt = true)
    {
        if (!isset(static::$dispatcher)) {
            return true;
        }

        $method = $halt ? 'until' : 'fire';

        $result = $this->filterFactorEventResults(
            $this->fireCustomFactorEvent($event, $method)
        );

        if ($result === false) {
            return false;
        }

        return !empty($result) ? $result : static::$dispatcher->{$method}(
            "dispenser.factor.{$event}: ".static::class, $this
        );
    }

    /**
     * @param string $event
     * @param string $method
     * @return mixed|null
     */
    protected function fireCustomFactorEvent($event, $method)
    {
        if (!isset($this->events[$event])) {
            return;
        }

        $result = static::$dispatcher->$method(new $this->events[$event]($this));

        if (!is_null($result)) {
            return $result;
        }
    }

    /**
     * @param mixed $result
     * @return mixed
     */
    protected function filterFactorEventResults($result)
    {
        if (is_array($result)) {
            $result = array_filter($result, function ($response) {
                return !is_null($response);
            });
        }

        return $result;
    }

    /**
     * @param \Closure|string $callback
     * @return void
     */
    public static function activating($callback)
    {
        static::registerFactorEvent('activating', $callback);
    }

    /**
     * @param \Closure|string $callback
     * @return void
     */
    public static function activated($callback)
    {
        static::registerFactorEvent('activated', $callback);
    }

    /**
     * @param \Closure|string $callback
     * @return void
     */
    public static function deactivating($callback)
    {
        static::registerFactorEvent('deactivating', $callback);
    }

    /**
     * @param \Closure|string $callback
     * @return void
     */
    public static function deactivated($callback)
    {
        static::registerFactorEvent('deactivated', $callback);
    }

    /**
     * @return void
     */
    public static function flushEventListeners()
    {
        if (!isset(static::$dispatcher)) {
            return;
        }

        foreach (static::getObservableEvents() as $event) {
            static::$dispatcher->forget("dispenser.factor.{$event}: ".static::class);
        }
    }

    /**
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     * @return void
     */
    public static function setEventDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * @return void
     */
    public static function unsetEventDispatcher()
    {
        static::$dispatcher = null;
    }
}
