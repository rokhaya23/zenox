<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $questions = Question::all();

            return response()->json([
                "status" => 200,
                "message" => "Liste des questions",
                "questions" => $questions
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
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'body' => 'required'
            ]);

            $question = new Question();
            $question->title = $request->title;
            $question->body = $request->body;
            $question->user_id = auth()->id(); // Assuming you have authentication
            $question->save();
            return response()->json([
                "status" => 200,
                "message" => "Question créée avec succès!",
                "question" => $question
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        try {
            $question = Question::findOrFail($question);

            return response()->json([
                "status" => 200,
                "message" => "Détails de la question",
                "question" => $question
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
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        try {
            $request->validate([
                'title' => 'required',
                'content' => 'required'
            ]);

            $question = Question::findOrFail($question);
            $question->title = $request->title;
            $question->content = $request->body;
            $question->save();

            return response()->json([
                "status" => 200,
                "message" => "Question mise à jour avec succès!",
                "question" => $question
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
    public function destroy(Question $question)
    {
        try {
            $question = Question::findOrFail($question);
            $question->delete();

            return response()->json([
                "status" => 200,
                "message" => "Question supprimée avec succès!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "message" => $e->getMessage()
            ]);
        }
    }

}
