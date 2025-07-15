<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $offers = $this->offerService->getActiveOffers();
        return response()->json([
            'success' => true,
            'data' => $offers,
        ]);
    }

    /**
     * Display a listing of the current user's offers.
     *
     * @return JsonResponse
     */
    public function myOffers(): JsonResponse
    {
        $offers = $this->offerService->getCurrentUserOffers();
        return response()->json([
            'success' => true,
            'data' => $offers,
        ]);
    }

    /**
     * Store a newly created offer in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:5120', // 5MB max
            'price' => 'nullable|numeric|min:0',
            'valid_until' => 'nullable|date|after:today',
            'additional_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('offers', 'public');
            $data['image'] = $path;
        }

        $data['user_id'] = Auth::id();
        $offer = $this->offerService->createOffer($data);

        return response()->json([
            'success' => true,
            'message' => 'Offer created successfully',
            'data' => $offer,
        ], 201);
    }

    /**
     * Display the specified offer.
     *
     * @param Offer $offer
     * @return JsonResponse
     */
    public function show(Offer $offer): JsonResponse
    {
        $offer->load('user');
        return response()->json([
            'success' => true,
            'data' => $offer,
        ]);
    }

    /**
     * Update the specified offer in storage.
     *
     * @param Request $request
     * @param Offer $offer
     * @return JsonResponse
     */
    public function update(Request $request, Offer $offer): JsonResponse
    {
        // Check if user owns this offer
        if ($offer->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image|max:5120', // 5MB max
            'price' => 'nullable|numeric|min:0',
            'valid_until' => 'nullable|date|after:today',
            'is_active' => 'sometimes|boolean',
            'additional_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('offers', 'public');
            $data['image'] = $path;
        }

        $offer = $this->offerService->updateOffer($offer, $data);

        return response()->json([
            'success' => true,
            'message' => 'Offer updated successfully',
            'data' => $offer,
        ]);
    }

    /**
     * Remove the specified offer from storage.
     *
     * @param Offer $offer
     * @return JsonResponse
     */
    public function destroy(Offer $offer): JsonResponse
    {
        // Check if user owns this offer
        if ($offer->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action',
            ], 403);
        }

        $this->offerService->deleteOffer($offer);

        return response()->json([
            'success' => true,
            'message' => 'Offer deleted successfully',
        ]);
    }
}
