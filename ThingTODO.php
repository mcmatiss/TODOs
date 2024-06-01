<?php
class ThingTODO
{
    private string $name;
    private string $displayName;
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->displayName = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDisplayName(): string
    {
        return $this->displayName;
    }
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }
}
