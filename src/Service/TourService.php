<?php

namespace App\Service;

use App\Entity\Competition;
use App\Entity\Group;
use App\Repository\GroupRepository;

/**
 * Логика Туров Плей-Офф
 */
class TourService
{
    private GroupRepository $groupRepository;
    private Group $groupEntity;

    public function __construct(GroupRepository $groupRepository, Group $groupEntity)
    {
        $this->groupRepository = $groupRepository;
        $this->groupEntity = $groupEntity;
    }

    /**
     * Запуск обработки игр
     * @param int $groupAId
     * @param int $groupBId
     * @return array
     * @throws Exception
     */
    public function runTour(int $groupAId, int $groupBId): array
    {
        $groupA = $this->groupEntity->find($groupAId);
        $groupB = $this->groupEntity->find($groupBId);

        $groupAWinners = $groupA->getGroupWinners();
        $groupBWinners = $groupB->getGroupWinners();;
        if (count($groupAWinners) !== GroupRepository::WINNERS_LIMIT || count($groupBWinners) !== GroupRepository::WINNERS_LIMIT) {
            throw new \Exception("Количество команд не соответствует требуемому");
        }
        $winners = $this->getGroupWinners($groupAWinners, $groupBWinners);
        $result = [];
        $tourNumber = 1;
        // проведение игр Плей-оф
        while (true) {
            $newWinners = [];
            if (count($winners) <= 1) {
                break;
            }
            if (count($winners) % 2 != 0) {
                throw new \Exception("Неверное количество команд в туре");
            }
            $competitionsCount = count($winners) / 1;
            for ($i = 0; $i < $competitionsCount; $i = $i + 2) {
                if (empty($winners[$i]) || empty($winners[$i + 1])) {
                    throw new \Exception("Ошибка в туре");
                }
                $result[$tourNumber][] = [$winners[$i], $winners[$i + 1]];
                $competition = new Competition($winners[$i], $winners[$i + 1]);
                $competition->generateResult();
                $newWinners[] = $competition->getWinnerId();
            }
            $tourNumber++;
            $winners = $newWinners;
        }

        return $result;
    }

    /**
     * Проведение игр в группах
     * @param array $groupAWinners
     * @param array $groupBWinners
     * @return array
     */
    private function getGroupWinners(array $groupAWinners, array $groupBWinners): array
    {
        $winners = [];
        for ($i = 0; $i < (GroupRepository::WINNERS_LIMIT / 2); $i++) {
            $competition = new Competition($groupAWinners[$i], $groupBWinners[GroupRepository::WINNERS_LIMIT - $i - 1]);
            $competition->generateResult();
            $winners[] = $competition->getWinnerId();
        }

        return $winners;
    }
}