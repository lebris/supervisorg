<?php

namespace Supervisorg\Services\Processes;

use Supervisorg\Domain\Process;

interface Filter
{
    public function filter(array $processList);

    public function isFiltered(Process $process);
}
