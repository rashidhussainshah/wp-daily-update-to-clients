<?php

namespace App\Http\Controllers;

use App\Models\DeveloperCard;
use App\Models\DeveloperCategory;
use App\utils\traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;

class DeveloperCategoryController extends Controller
{
    use ApiResponseTrait;

// Method to return all developer categories
    public function index()
    {
        try {
            $categories = DeveloperCategory::all();
            return $this->successResponse($categories);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve developer categories');
        }
    }

    // Method to return all developers for a specific developer category
    public function show($id)
    {
        try {
            if ($id === 'all') { // If the user passes 'all' as the key
                $developers = DeveloperCard::where('for_all_filter', true)
                    ->with('expertises')
                    ->get();
            } else {
                $developers = DeveloperCard::where('developer_category_id', $id)
                    ->with('expertises')
                    ->get();
            }

            if ($developers->isEmpty()) {
                return $this->errorResponse('No developers found for the specified category', 404);
            }

            return $this->successResponse($developers);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve developers');
        }
    }
}
