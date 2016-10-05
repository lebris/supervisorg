<?php

namespace Supervisorg\Domain;

use Supervisorg\Services\Processes\Filter;

class LogicalGroup
{
    private
        $name,
        $icon,
        $default,
        $regex,
        $filter;

    public function __construct($name, array $definition, Filter $filter)
    {
        $this->name = $name;
        $this->filter = $filter;

        $definition = $this->ensureDefinitionIsValid($definition);

        $this->regex = sprintf('~%s~', $definition['regex']);
        $this->icon = $definition['icon'];
        $this->default = $definition['defaultView'] === true;
    }

    private function ensureDefinitionIsValid(array $definition)
    {
        $defaultValues = [
            'defaultView' => false,
            'icon' => 'circle-o-notch',
        ];

        $definition = $definition + $defaultValues;

        $requiredFields = ['regex'];
        foreach($requiredFields as $field)
        {
            if(! isset($definition[$field]))
            {
                throw new \InvalidArgumentException(sprintf(
                    'Missing field %s in logical group %s definition',
                    $field,
                    $this->name
                ));
            }
        }

        return $definition;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function isDefault()
    {
        return $this->default;
    }

    public function belongTo(Process $process, $value)
    {
        if($this->filter->isFiltered($process))
        {
            return false;
        }

        if($this->belongToAny($process))
        {
            return $this->getValue($process) === $value;
        }

        return false;
    }

    public function belongToAny(Process $process)
    {
        return preg_match($this->regex, $process->getName());
    }

    public function getValue(Process $process)
    {
        if(preg_match($this->regex, $process->getName(), $matches))
        {
            if(isset($matches['groupValue']))
            {
                return $matches['groupValue'];
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'Invalid regex for logical group %s : missing "groupValue" capturing group',
            $this->name
        ));
    }

    public function getProcessName(Process $process)
    {
        $value = $process->getName();

        if(preg_match($this->regex, $value, $matches))
        {
            if(isset($matches['process']))
            {
                $value = $matches['process'];
            }
        }

        return $value;
    }
}
