<?php

namespace Controller;

use App\Entity\Group;
use App\Service\TourService;

class TourController extends AbstractController
{

    /**
     * @Route("/", name="tour")
     */
    public function tourAction (TourService $service)
    {
        try {
            $result = $service->runTour(Group::GROUP_A_ID, Group::GROUP_B_ID);
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            $result = ['message' => 'Ошибка'];
        }

        return json_encode($result);
    }
}