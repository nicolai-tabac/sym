<?php

namespace App\Repository;

/**
 * Репозиторий сущности Группа(Дивизион)
 */
class GroupRepository extends AdstractRepository
{
    public const WINNERS_LIMIT = 4;

    /**
     * Команды вышедшие из группы
     * @param int $groupId
     * @return array
     */
    public function getGroupWinners(int $groupId): array
    {
        $qb = $this->select([
            'c.winnerId',
            'COUNT(*) as winTimes',
        ])
            ->from('competitions as c')
            ->join('team as t1 on t1.id = c.team1Id')
            ->join('team as t2 on t2.id = c.team1Id')
            ->where('t1.grouId = :groupId')
            ->where('t2.grouId = :groupId')
            ->where('g.id > :id')
            ->setParameter('groupId', $groupId)
            ->groupBy('c.winnerId')
            ->orderBy('winTimes', 'DESC')
            ->limit(self::WINNERS_LIMIT)
        ;

        $query = $qb->getQuery();

        return $query->execute()->toArray();
    }
}