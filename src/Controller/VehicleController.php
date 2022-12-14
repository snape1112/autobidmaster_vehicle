<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\VehicleService;

class VehicleController extends AbstractController
{
    private VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     * @Route("/vehicles", name="get_all_vehicles", methods={"GET"})
     */
    public function getAllVehicles(Request $request): JsonResponse
    {
        $params = $request->query->all();
        $data = $this->vehicleService->getVehiclesList($params);

        return new JsonResponse(['success' => true, 'data' => $data]);
    }
}
