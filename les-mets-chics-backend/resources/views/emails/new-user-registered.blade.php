<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .user-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔔 Nouveau compte en attente</h1>
    </div>
    
    <div class="content">
        <p>Bonjour,</p>
        
        <p>Un nouvel utilisateur vient de s'inscrire sur <strong>Les Mets Chics</strong> et attend la validation de son compte.</p>
        
        <div class="user-info">
            <p><strong>👤 Nom :</strong> {{ $user->name }}</p>
            <p><strong>📧 Email :</strong> {{ $user->email }}</p>
            <p><strong>📅 Date d'inscription :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</p>
            <p><strong>📊 Statut :</strong> <span style="color: orange;">En attente</span></p>
        </div>
        
        <p>Pour valider ce compte, connectez-vous à l'administration :</p>
        
        <a href="{{ url('/admin/users') }}" class="button">
            Gérer les utilisateurs
        </a>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par Les Mets Chics</p>
        </div>
    </div>
</body>
</html>
