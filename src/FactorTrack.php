<?php

namespace Daniser\InquiryDispenser;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FactorTrack extends Collection implements Contracts\FactorTrack
{
    /** @var Factor $factor */
    protected $factor;

    /** @var Builder $query */
    protected $query;

    public function __construct(Factor $factor)
    {
        $this->factor = $factor;

        $connection = config('dispenser.connection.database.connection', 'default');
        $table = config('dispenser.connection.database.table', 'factor_track');
        $this->query = DB::connection($connection)->table($table);

        $trackData = $this->query
            ->where('instance', $this->factorSignature())->orderBy('tracked_at')->get(['active', 'tracked_at'])
            ->map(function ($snapshot) {
                $active = (bool)data_get($snapshot, 'active');
                $end = new \DateTime(data_get($snapshot, 'tracked_at'));
                return new TrackPeriod($end, $end, $active);
            });

        parent::__construct($trackData);
    }

    protected function factorSignature()
    {
        static $signature;
        if (!isset($signature)) $signature = md5(serialize($this->factor));
        return $signature;
    }

    public function getLastState()
    {
        $instance = $this->factorSignature();
        $result = $this->query->where('instance', $instance)->orderByDesc('tracked_at')->first(['active']);
        return (bool)data_get($result, 'active', false);
    }

    public function snapshot($force = false)
    {
        if (!$force) {
            $force = $this->factor->active !== $this->getLastState();
        }

        if ($force) $this->query->insert([
            'instance' => $this->factorSignature(),
            'active' => $this->factor->active,
        ]);
    }

    public function getSecondsActive()
    {

    }
}
