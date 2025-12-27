<?php

namespace Shimango\Gophr\Resources;

use Shimango\Gophr\Exceptions\InvalidReturnTypeException;
use Shimango\Gophr\Http\Responses\Parcels\CreateParcelResponse;
use Shimango\Gophr\Http\Responses\Parcels\DeleteParcelResponse;
use Shimango\Gophr\Http\Responses\Parcels\GetParcelResponse;
use Shimango\Gophr\Http\Responses\Parcels\ListParcelsResponse;
use Shimango\Gophr\Http\Responses\Parcels\UpdateParcelResponse;

/**
 * A parcel resource represents the data associated with a physical parcel. A parcel belongs to a delivery. A delivery
 * can have many parcels.
 * @package Shimango\Gophr\Resources
 */
final class Parcel extends AbstractResource
{
    /**
     * Creates a parcel
     * @throws InvalidReturnTypeException
     */
    public function createParcel(string $jobId, string $deliveryId, array $parcelData): CreateParcelResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s/parcels', $jobId, $deliveryId) . $this->generateUrlParameters();
        /** @var CreateParcelResponse $gophrResponse */
        $gophrResponse = $this->create($apiEndPoint, $parcelData, CreateParcelResponse::class);
        return $gophrResponse;
    }

    /**
     * Gets a parcel
     * @throws InvalidReturnTypeException
     */
    public function getParcel(string $jobId, string $deliveryId, string $parcelId): GetParcelResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s/parcels/%s', $jobId, $deliveryId, $parcelId) . $this->generateUrlParameters();
        /** @var GetParcelResponse $response */
        $response = $this->read($apiEndPoint, GetParcelResponse::class);
        return $response;
    }

    /**
     * Updates a parcel
     * @throws InvalidReturnTypeException
     */
    public function updateParcel(string $jobId, string $deliveryId, string $parcelId, array $deliveryData): UpdateParcelResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s/parcels/%s', $jobId, $deliveryId, $parcelId) . $this->generateUrlParameters();
        /** @var UpdateParcelResponse $gophrResponse */
        $gophrResponse = $this->update($apiEndPoint, $deliveryData, UpdateParcelResponse::class);
        return $gophrResponse;
    }

    /**
     * deletes a parcel
     * @throws InvalidReturnTypeException
     */
    public function deleteParcel(string $jobId, string $deliveryId, string $parcelId): DeleteParcelResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s/parcels/%s', $jobId, $deliveryId, $parcelId) . $this->generateUrlParameters();
        /** @var DeleteParcelResponse $gophrResponse */
        $gophrResponse = $this->delete($apiEndPoint, DeleteParcelResponse::class);
        return $gophrResponse;
    }

    /**
     * Lists parcels
     * @throws InvalidReturnTypeException
     */
    public function listParcels(string $jobId, string $deliveryId): ListParcelsResponse
    {
        $apiEndPoint = $this->gophrRequest->getEndpoint() . sprintf('/jobs/%s/deliveries/%s/parcels', $jobId, $deliveryId) . $this->generateUrlParameters();
        /** @var ListParcelsResponse $response */
        $response = $this->read($apiEndPoint, ListParcelsResponse::class);
        return $response;
    }
}
