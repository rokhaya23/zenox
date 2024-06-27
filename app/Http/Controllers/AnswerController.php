<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $responses = Answer::all();

            return response()->json([
                "status" => 200,
                "message" => "Liste des réponses",
                "responses" => $responses
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'body' => 'required',
                'question_id' => 'required|exists:questions,id'
            ]);

            $response = new Answer();
            $response->body = $request->body;
            $response->question_id = $request->question_id;
            $response->user_id = auth()->id(); // Assuming you have authentication
            $response->save();

            return response()->json([
                "status" => 201,
                "message" => "Réponse créée avec succès!",
                "response" => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        try {
            $response = Answer::findOrFail($answer);

            return response()->json([
                "status" => 200,
                "message" => "Détails de la réponse",
                "response" => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        try {
            $request->validate([
                'body' => 'required'
            ]);

            $response = Answer::findOrFail($answer);
            $response->body = $request->body;
            $response->save();

            return response()->json([
                "status" => 200,
                "message" => "Réponse mise à jour avec succès!",
                "response" => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        try {
            $response = Answer::findOrFail($answer);
            $response->delete();

            return response()->json([
                "status" => 200,
                "message" => "Réponse supprimée avec succès!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

}
