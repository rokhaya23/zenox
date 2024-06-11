<?php
namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            // Création de l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Recherche du rôle par défaut "Apprenant"
            $defaultRole = Role::where('name', 'Apprenant')->first();

            if ($defaultRole) {
                // Assigner le rôle à l'utilisateur
                $user->roles()->attach($defaultRole->id);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Le rôle par défaut "Apprenant" est introuvable.'
                ], 500);
            }

            // Authentification de l'utilisateur
            Auth::login($user);

            return response()->json([
                'status' => 200,
                'message' => 'Utilisateur enregistré avec succès!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return response()->json([
                    'status' => 200,
                    'message' => 'Utilisateur connecté avec succès!',
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Identifiants invalides'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => 200,
                'message' => 'Déconnexion réussie'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
