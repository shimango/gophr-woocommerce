<?php

namespace Shimango\Gophr\Resources;

use Shimango\Gophr\Exceptions\InvalidReturnTypeException;
use Shimango\Gophr\Http\Responses\Deliveries\CancelDeliveryResponse;
use Shimango\Gophr\Http\Responses\Deliveries\CreateDeliveryResponse;
use Shimango\Gophr\Http\Responses\Deliveries\GetDeliveryResponse;
use Shimango\Gophr\Http\Responses\Deliveries\ListDeliveriesResponse;
use Shimango\Gophr\Http\Responses\Deliveries\ProgressDeliveryStatusResponse;
use Shimango\Gophr\Http\Responses\Deliveries\UpdateDeliveryResponse;

/**
 * A delivery represents a specific pickup / dropoff combination. A delivery can have many parcels and belongs to a job.
 * @package Shimango\Gophr\Resources
 */
final class Delivery extends AbstractResource
{
    /**
     * Creates delivery
     * @throws InvalidReturnTypeException
     */
    public function createDelivery(string $jobId, array $deliveryData): CreateDeliveryResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries', $jobId) . $this->generateUrlParameters();
        /** @var CreateDeliveryResponse $response */
        $response = $this->create($apiEndPoint, $deliveryData, CreateDeliveryResponse::class);
        return $response;
    }

    /**
     * Gets a delivery
     * @throws InvalidReturnTypeException
     */
    public function getDelivery(string $jobId, string $deliveryId): GetDeliveryResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s', $jobId, $deliveryId) . $this->generateUrlParameters();
        /** @var GetDeliveryResponse $response */
        $response = $this->read($apiEndPoint, GetDeliveryResponse::class);
        return $response;
    }

    /**
     * Updates a delivery
     * @throws InvalidReturnTypeException
     */
    public function updateDelivery(string $jobId, string $deliveryId, array $deliveryData): UpdateDeliveryResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s', $jobId, $deliveryId) . $this->generateUrlParameters();
        /** @var UpdateDeliveryResponse $gophrResponse */
        $gophrResponse = $this->update($apiEndPoint, $deliveryData, UpdateDeliveryResponse::class);
        return $gophrResponse;
    }

    /**
     * Lists deliveries
     * @throws InvalidReturnTypeException
     */
    public function listDeliveries(string $jobId): ListDeliveriesResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries', $jobId) . $this->generateUrlParameters();
        /** @var ListDeliveriesResponse $response */
        $response = $this->read($apiEndPoint, ListDeliveriesResponse::class);
        return $response;
    }

    /**
     * Cancels a delivery
     * @throws InvalidReturnTypeException
     */
    public function cancelDelivery(string $jobId, string $deliveryId, array $deliveryData): CancelDeliveryResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s/cancel', $jobId, $deliveryId) . $this->generateUrlParameters();
        /** @var CancelDeliveryResponse $response */
        $response = $this->create($apiEndPoint, $deliveryData, CancelDeliveryResponse::class);
        return $response;
    }

    /**
     * Progresses a delivery status. This functionality is only available on the sandbox server.
     * @return progressDeliveryStatusResponse
     * @throws InvalidReturnTypeException
     */
    public function progressDeliveryStatus(string $jobId, string $deliveryId): progressDeliveryStatusResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s/progress', $jobId, $deliveryId) . $this->generateUrlParameters();
        /** @var progressDeliveryStatusResponse $response */
        $response = $this->create($apiEndPoint, [],progressDeliveryStatusResponse::class);
        return $response;
    }
}
