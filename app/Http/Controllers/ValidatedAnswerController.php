<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Role;
use App\Models\Validated_answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ValidatedAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Validated_answer $validated_answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Validated_answer $validated_answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Validated_answer $validated_answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Validated_answer $validated_answer)
    {
        //
    }


    public function validateAnswer(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:answers,id',
        ]);

        $answerId = $request->input('answer_id');
        $supervisorId = Auth::id();

        // Vérifier si l'utilisateur authentifié est un superviseur
        $supervisor = Auth::user();
        if (!$supervisor->hasRole('Superviseur')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'êtes pas autorisé à valider des réponses',
            ], 403);
        }

        try {
            $existingValidation = Validated_answer::where('answer_id', $answerId)
                ->where('supervisor_id', $supervisorId)
                ->first();

            if ($existingValidation) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Cette réponse a déjà été validée par ce superviseur',
                ], 400);
            }

            $validatedAnswer = new Validated_answer();
            $validatedAnswer->answer_id = $answerId;
            $validatedAnswer->supervisor_id = $supervisorId;
            $validatedAnswer->save();

            $answer = Answer::find($answerId);
            $learner = $answer->user;

            // Comptabiliser le nombre de réponses validées
            $validatedCount = Validated_answer::whereHas('answer', function ($query) use ($learner) {
                $query->where('user_id', $learner->id);
            })->count();

            // Debugging information
            Log::info('Validated count for user ' . $learner->id . ': ' . $validatedCount);

            if ($validatedCount >= 10 && !$learner->hasRole('Superviseur')) {
                // Ajouter le rôle superviseur à l'utilisateur
                $learner->roles()->attach(Role::where('name', 'Superviseur')->first());

                // Debugging information
                Log::info('User ' . $learner->id . ' promoted to Superviseur');
            }

            return response()->json([
                'status' => 200,
                'message' => 'Réponse validée avec succès',
            ], 200);
        } catch (\Exception $e) {
            // Debugging information
            Log::error('Validation error: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

