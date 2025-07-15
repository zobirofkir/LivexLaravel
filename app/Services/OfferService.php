<?php

namespace App\Services;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class OfferService
{
    /**
     * Get all active offers
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getActiveOffers(int $perPage = 10): LengthAwarePaginator
    {
        return Offer::active()
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get offers by user
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getOffersByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Offer::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get current user's offers
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getCurrentUserOffers(int $perPage = 10): LengthAwarePaginator
    {
        return $this->getOffersByUser(Auth::id(), $perPage);
    }

    /**
     * Create a new offer
     *
     * @param array $data
     * @return Offer
     */
    public function createOffer(array $data): Offer
    {
        // Set user_id to current user if not provided
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        return Offer::create($data);
    }

    /**
     * Update an offer
     *
     * @param Offer $offer
     * @param array $data
     * @return Offer
     */
    public function updateOffer(Offer $offer, array $data): Offer
    {
        $offer->update($data);
        return $offer;
    }

    /**
     * Delete an offer
     *
     * @param Offer $offer
     * @return bool
     */
    public function deleteOffer(Offer $offer): bool
    {
        return $offer->delete();
    }
}