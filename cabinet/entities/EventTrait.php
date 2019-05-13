<?php

namespace cabinet\entities;

trait EventTrait
{
    private $events = [];

    protected function recordEvent($event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvent(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}