<?php

namespace App\Entity;

/**
 * Класс отвечающий за сущность Команда
 */
class Team
{

    private $id;

    private $name;

    private $groupId;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }
}