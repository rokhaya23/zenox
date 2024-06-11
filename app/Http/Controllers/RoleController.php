<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::all();

            return response()->json([
                'status' => 200,
                'message' => 'Liste des rôles',
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:roles',
            ]);

            // Création du rôle
            $role = Role::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'Rôle créé avec succès!',
                'role' => $role
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
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
            ]);

            // Recherche et mise à jour du rôle
            $role = Role::findOrFail($id);
            $role->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Rôle mis à jour avec succès!',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
