<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;


use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    // Afficher tous les contacts
    public function index()
    {
        $contacts = Contact::all();  // Récupérer tous les contacts
        return response()->json($contacts);
    }

    // Afficher un contact par ID
    public function show($id)
    {
        $contact = Contact::find($id);  // Trouver le contact par ID

        if (!$contact) {
            return response()->json(['message' => 'Contact non trouvé.'], 404);
        }

        return response()->json($contact);
    }

    // Ajouter un contact (POST)
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10',
            'message' => 'required|string|max:1000',
        ]);

        // Ajouter un contact avec un statut par défaut "Pas encore"
        $contact = Contact::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'message' => $validatedData['message'],
            'contact_date' => now(),
            'status' => 'Pas encore',  // Valeur par défaut
        ]);

        return response()->json([
            'message' => 'Contact enregistré avec succès.',
            'contact' => $contact,
        ], 201);
    }

    // Supprimer un contact par ID
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact non trouvé.'], 404);
        }

        $contact->delete();  // Supprimer le contact

        return response()->json(['message' => 'Contact supprimé avec succès.']);
    }

    // Afficher les 5 derniers contacts ajoutés
    public function latest()
    {
    // Récupérer les 5 derniers contacts triés par `created_at` en ordre décroissant
    $contacts = Contact::orderBy('created_at', 'desc')->limit(5)->get();

    // Vérification des contacts avec débogage
    \Log::info('Contacts récupérés : ', $contacts->toArray());

    // Vérifier si des contacts existent
    if ($contacts->isEmpty()) {
        return response()->json(['message' => 'Aucun contact trouvé jjjjjj.'], 404);
    }

    // Retourner les contacts sous forme de JSON
    return response()->json($contacts);
    }

    // Sauvegarder une réponse et mettre à jour le statut
    public function respond(Request $request, $id)
    {
        // Valider les données de la réponse
        $validatedData = $request->validate([
            'response' => 'required|string|max:1000',
        ]);

        // Récupérer le contact
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact non trouvé.'], 404);
        }

        // Mettre à jour les champs `response`, `status` et `response_date`
        $contact->update([
            'response' => $validatedData['response'],
            'status' => 'Répondu',
            'response_date' => now(),
        ]);

        return response()->json([
            'message' => 'Réponse enregistrée avec succès.',
            'contact' => $contact,
        ]);
    }

   

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $details = [
            'email' => $request->email,
            'name' => 'Utilisateur',
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        Notification::route('mail', $request->email)->notify(new EmailNotification($details));

        return response()->json(['message' => 'Email envoyé avec succès !'], 200);
    }


}


 