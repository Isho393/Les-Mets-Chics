<?php

namespace App\Services;

class ContentModerationService
{
    /**
     * Liste des mots interdits (insultes, propos racistes, etc.)
     */
    private array $bannedWords = [
        // Insultes courantes
        'connard', 'connasse', 'salope', 'pute', 'enculé', 'enculer',
        'putain', 'merde', 'chier', 'con', 'conne', 'débile', 'idiot',
        'imbécile', 'crétin', 'abruti', 'taré', 'fdp', 'ntm',
        
        // Propos racistes et discriminatoires
        'nègre', 'négro', 'bamboula', 'bougnoule', 'youpin', 'youtre',
        'raton', 'bicot', 'crouille', 'sale arabe', 'sale noir', 'sale juif',
        'sale blanc', 'sale musulman', 'terroriste', 'singe',
        
        // Variantes avec caractères spéciaux
        'c0nn4rd', 'p*te', 'enc*lé', 'f.d.p', 'n.t.m',
    ];

    /**
     * Vérifie si le contenu contient des mots interdits
     */
    public function containsBannedWords(string $content): bool
    {
        $content = $this->normalizeText($content);
        
        foreach ($this->bannedWords as $word) {
            $pattern = $this->createPattern($word);
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Normalise le texte pour la détection
     */
    private function normalizeText(string $text): string
    {
        // Convertir en minuscules
        $text = mb_strtolower($text, 'UTF-8');
        
        // Remplacer les caractères spéciaux souvent utilisés pour contourner les filtres
        $replacements = [
            '0' => 'o',
            '1' => 'i',
            '3' => 'e',
            '4' => 'a',
            '5' => 's',
            '7' => 't',
            '8' => 'b',
            '@' => 'a',
            '$' => 's',
            '*' => '',
            '.' => '',
            '-' => '',
            '_' => '',
            ' ' => '',
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    /**
     * Crée un pattern regex pour détecter les variantes d'un mot
     */
    private function createPattern(string $word): string
    {
        $word = preg_quote($this->normalizeText($word), '/');
        return '/\b' . $word . '\b/iu';
    }

    /**
     * Retourne un message d'erreur approprié
     */
    public function getErrorMessage(): string
    {
        return 'Votre commentaire contient des termes inappropriés. Merci de rester respectueux.';
    }
}
