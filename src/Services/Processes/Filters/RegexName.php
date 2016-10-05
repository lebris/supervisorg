<?php

namespace Supervisorg\Services\Processes\Filters;

use Supervisorg\Services\Processes\Filter;
use Supervisorg\Domain\Process;

class RegexName implements Filter
{
    private
        $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function filter(array $processList)
    {
        foreach($processList as $index => $process)
        {
            if( ! $process instanceof Process)
            {
                continue;
            }

            if($this->isFiltered($process))
            {
                unset($processList[$index]);
            }
        }

        return $processList;
    }

    public function isFiltered(Process $process)
    {
        return (bool) preg_match($this->pattern, $process->getName());
    }
}
