<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Uitilisateur;
use App\Models\User;
use Illuminate\Http\Request;

class UitilisateurController extends Controller
{
    public function index()
    {
        try {
            // Récupération de tous les utilisateurs
            $users = User::all();

            return response()->json([
                'status' => 200,
                'message' => 'Liste des utilisateurs',
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Recherche de l'utilisateur par son ID
            $user = User::findOrFail($id);

            return response()->json([
                'status' => 200,
                'message' => 'Détails de l\'utilisateur',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 404); // Utilisateur non trouvé
        }
    }

    public function store(Request $request)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            // Création de l'utilisateur
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);

            // Ajout du rôle par défaut à l'utilisateur
            $defaultRole = Role::where('name', 'Apprenant')->first();
            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id);
            }

            return response()->json([
                'status' => 201,
                'message' => 'Utilisateur créé avec succès!',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|string|min:8', // Le mot de passe est facultatif
                'role_id' => 'sometimes|required|exists:roles,id', // Vérifie que le rôle existe
            ]);

            // Recherche de l'utilisateur
            $user = User::findOrFail($id);

            // Mise à jour des données de l'utilisateur
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : $user->password,
            ]);

            // Mise à jour du rôle de l'utilisateur
            if (isset($validatedData['role_id'])) {
                $role = Role::findOrFail($validatedData['role_id']);
                $user->roles()->sync([$role->id]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Utilisateur mis à jour avec succès!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            // Recherche de l'utilisateur par son ID
            $user = User::findOrFail($id);

            // Suppression de l'utilisateur
            $user->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Utilisateur supprimé avec succès!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500); // Erreur interne du serveur
        }
    }




}
