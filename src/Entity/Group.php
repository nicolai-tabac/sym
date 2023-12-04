<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\Collection;

/**
 * Класс отвечающий за сущность Группы(Дивизиона)
 */
class Group
{

    public const GROUP_A_ID = 1;

    public const GROUP_B_ID = 2;

    private int $id;

    private string $name;

    private Collection $teams;

    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTeamsCollection(): Collection
    {
        return $this->teams;
    }

    /**
     * Команды вышедшие из группы
     * @return array
     */
    public function getGroupWinners(): array
    {
        $this->generateCompetitions();

        return $this->groupRepository->getGroupWinners($this->getId());
    }

    /**
     * Генерируем игры в дивизионе
     */
    public function generateCompetitions()
    {
        $teams = $this->teams;
        $teamsCount = count($teams);
        if ($teamsCount === 0) {
            return;
        }
        for ($i = 0; $i < $teamsCount - 1; $i++) {
            for ($j = $i + 1; $j < $teamsCount; $j++) {
                $competition = new Competition($teams[$i]->getId(), $teams[$j]->getId());
                $competition->generateResult();
            }
        }
    }

}