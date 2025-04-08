<?php

namespace App\Http\Controllers;

use App\Models\ClientPortfolio;
use Illuminate\Http\Request;

class ClientPortfolioController extends Controller
{
    // Display all client portfolios for the authenticated user
    public function index()
    {
        // Get portfolios for the authenticated user
        $portfolios = ClientPortfolio::where('user_id', auth()->id())->get();
        return response()->json($portfolios);
    }

    // Store a new client portfolio
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string', // Image URL or path
            'experience' => 'nullable|array', // Ensure experience is an array
            'expertise' => 'nullable|array', // Ensure expertise is an array
            'knowledge' => 'nullable|array', // Ensure knowledge is an array
            'client_name' => 'nullable|array', // Ensure client names are an array
            'client_review' => 'nullable|array', // Ensure client reviews are an array
            'whatsapp' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
        ]);

        // Add the authenticated user's ID to the request data
        $portfolioData = $request->all();
        $portfolioData['user_id'] = auth()->id();  // Store the logged-in user's ID in the portfolio

        // Create the portfolio
        $portfolio = ClientPortfolio::create($portfolioData);

        return response()->json($portfolio, 201);
    }

    // Display a single client portfolio (including user information)
    public function show($id)
    {
        // Find the portfolio by ID or fail
        $portfolio = ClientPortfolio::with('user')->findOrFail($id);  // 'user' is the relationship method we defined earlier

        return response()->json($portfolio);
    }

    // Update an existing client portfolio
    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'name' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string', // Image URL or path
            'experience' => 'nullable|array', // Ensure experience is an array
            'expertise' => 'nullable|array', // Ensure expertise is an array
            'knowledge' => 'nullable|array', // Ensure knowledge is an array
            'client_name' => 'nullable|array', // Ensure client names are an array
            'client_review' => 'nullable|array', // Ensure client reviews are an array
            'whatsapp' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
        ]);

        // Find the portfolio by ID
        $portfolio = ClientPortfolio::findOrFail($id);

        // Ensure that the user is authorized to update this portfolio (only the user who created it should be able to update it)
        if ($portfolio->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update the portfolio with the new data
        $portfolio->update($request->all());

        return response()->json($portfolio);
    }

    // Delete a client portfolio
    public function destroy($id)
    {
        // Find the portfolio by ID
        $portfolio = ClientPortfolio::findOrFail($id);

        // Ensure that the user is authorized to delete this portfolio
        if ($portfolio->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the portfolio
        $portfolio->delete();

        return response()->json(null, 204);
    }
}
