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
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
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
        .features {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .features ul {
            list-style: none;
            padding: 0;
        }
        .features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .features li:last-child {
            border-bottom: none;
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
        <h1>🎉 Bienvenue sur Les Mets Chics !</h1>
    </div>
    
    <div class="content">
        <p>Bonjour {{ $user->name }},</p>
        
        <div class="success-box">
            <h2 style="margin: 0;">✅ Votre compte a été activé !</h2>
        </div>
        
        <p>Nous sommes ravis de vous accueillir dans notre communauté de passionnés de gastronomie.</p>
        
        <p>Vous pouvez maintenant vous connecter et profiter de toutes les fonctionnalités :</p>
        
        <div class="features">
            <ul>
                <li>🍽️ Accéder à toutes nos recettes gastronomiques</li>
                <li>❤️ Liker vos recettes préférées</li>
                <li>💬 Commenter et partager vos avis</li>
                <li>📚 Explorer les différentes catégories</li>
                <li>👨‍🍳 Découvrir les créations de nos chefs</li>
            </ul>
        </div>
        
        <p style="text-align: center;">
            <a href="{{ config('app.frontend_url') }}/connexion" class="button">
                Se connecter maintenant
            </a>
        </p>
        
        <p>Bon appétit et bonne découverte ! 🍴</p>
        
        <div class="footer">
            <p>L'équipe Les Mets Chics</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
