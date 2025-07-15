<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Services\OfferService;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    protected OfferService $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * Display a listing of active offers.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $offers = $this->offerService->getActiveOffers();
        return OfferResource::collection($offers);
    }

    /**
     * Display a listing of the current user's offers.
     *
     * @return AnonymousResourceCollection
     */
    public function myOffers(): AnonymousResourceCollection
    {
        $offers = $this->offerService->getCurrentUserOffers();
        return OfferResource::collection($offers);
    }

    /**
     * Store a newly created offer in storage.
     *
     * @param StoreOfferRequest $request
     * @return OfferResource
     */
    public function store(StoreOfferRequest $request): OfferResource
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('offers', 'public');
            $data['image'] = $path;
        }

        $data['user_id'] = Auth::id();
        $offer = $this->offerService->createOffer($data);

        return (new OfferResource($offer))
            ->additional([
                'success' => true,
                'message' => 'Offer created successfully',
                'status' => 201
            ]);
    }

    /**
     * Display the specified offer.
     *
     * @param Offer $offer
     * @return OfferResource
     */
    public function show(Offer $offer): OfferResource
    {
        $offer->load('user');
        return (new OfferResource($offer))
            ->additional(['success' => true]);
    }

    /**
     * Update the specified offer in storage.
     *
     * @param UpdateOfferRequest $request
     * @param Offer $offer
     * @return OfferResource
     */
    public function update(UpdateOfferRequest $request, Offer $offer): OfferResource
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('offers', 'public');
            $data['image'] = $path;
        }

        $offer = $this->offerService->updateOffer($offer, $data);

        return (new OfferResource($offer))
            ->additional([
                'success' => true,
                'message' => 'Offer updated successfully'
            ]);
    }

    /**
     * Remove the specified offer from storage.
     *
     * @param Offer $offer
     * @return OfferResource|array
     */
    public function destroy(Offer $offer)
    {
        // Check if user owns this offer
        if ($offer->user_id !== Auth::id()) {
            return (new OfferResource([]))
                ->additional([
                    'success' => false,
                    'message' => 'Unauthorized action',
                    'status' => 403
                ]);
        }

        $this->offerService->deleteOffer($offer);
        
        return (new OfferResource([]))
            ->additional([
                'success' => true,
                'message' => 'Offer deleted successfully'
            ]);
    }
}
