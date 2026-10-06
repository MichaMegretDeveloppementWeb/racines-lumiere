<!DOCTYPE html>
<html lang="fr">
    <head><meta charset="utf-8"><title>Nouveau message via le site</title></head>
    <body>
        <h1>Nouveau message via le site</h1>
        <p><strong>Nom :</strong> {{ $contact->name }}</p>
        <p><strong>Adresse e-mail :</strong> {{ $contact->email }}</p>
        @if ($contact->phone !== null)
            <p><strong>Téléphone :</strong> {{ $contact->phone }}</p>
        @endif
        <p><strong>Date :</strong> {{ now()->timezone('Europe/Paris')->format('d/m/Y à H:i') }}</p>
        <p><strong>Message :</strong></p>
        <div style="white-space: pre-wrap">{{ $contact->message }}</div>
    </body>
</html>