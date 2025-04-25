<?php

namespace App\Http\Controllers;

use App\Models\ClientPortfolio;
use Illuminate\Http\Request;

class ClientPortfolioController extends Controller
{

    // Display a single client portfolio by its ID (public access)
    public function show($id)
    {
        // Find the portfolio by ID and load the associated user relationship
        $portfolio = ClientPortfolio::with('user')->find($id);

        // If portfolio not found, return a 404 response
        if (!$portfolio) {
            return response()->json(['error' => 'Portfolio not found'], 404);
        }

        // Return the portfolio along with the user data as JSON
        return response()->json($portfolio);
    }
}
