<?php

namespace Supervisorg\Services\Processes;

use Supervisorg\Domain\Process;

class FilterCollection implements Filter
{
    private
        $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    public function filter(array $processList)
    {
        foreach($this->filters as $filter)
        {
            $processList = $filter->filter($processList);
        }

        return $processList;
    }

    public function isFiltered(Process $process)
    {
        foreach($this->filters as $filter)
        {
            if($filter->isFiltered($process))
            {
                return true;
            }
        }

        return false;
    }
}
