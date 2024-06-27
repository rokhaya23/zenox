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
            $defaultRole = Role::where('name', 'Superviseur')->first();

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

            // Create an API token for the user
            $token = $user->createToken('token-name', ['*'])->plainTextToken;

            // Return JSON response
            return response()->json([
                'message' => 'User registered successfully and logged in.',
                'user' => $user,
                'token' => $token,
                'tokenExpiry' => now()->addMinutes(120)->format('Y-m-d H:i:s'),
            ], 201);
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
                $token=$user->createToken('token-name', ['*'],now()->addMinutes(60))->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'expireAt' => now()->addMinutes(60)->format('Y-m-d H:i:s'),
                ],200);
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
    public function refresh()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $token=$user->createToken('token-name', ['*'],now()->addMinutes(60))->plainTextToken;

        return response()->json([
            'token' => $token,
            'expireAt' => now()->addMinutes(60)->format('Y-m-d H:i:s'),
        ]);

    }


}
