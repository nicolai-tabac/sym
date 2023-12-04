<?php

namespace App\Entity;

/**
 * Сущность Игра между командами
 */
class Competition
{

    private int $id;

    private int $team1Id;

    private int $team2Id;

    private string $score;

    private int $winnerId;

    public function __construct(int $team1Id, int $team2Id)
    {
        $this->team1Id = $team1Id;
        $this->team2Id = $team2Id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTeam1Id()
    {
        return $this->team1Id;
    }

    public function getTeam2Id()
    {
        return $this->team2Id;
    }

    public function getWinnerId()
    {
        return $this->winnerId;
    }

    public function setScore(string $score)
    {
        $this->score = $score;
    }

    public function setWinner(int $winnerId)
    {
        $this->winnerId = $winnerId;
    }

    public function generateResult()
    {
        $competitionTeamsIds = [$this->team1Id, $this->team2Id];
        $winnerId = $competitionTeamsIds[rand(0, 1)];
        $score = $winnerId === $this->team1Id ? '1:0' : '0:1';
        $this->setWinner($winnerId);
        $this->setScore($score);
        $this->save();
    }
}