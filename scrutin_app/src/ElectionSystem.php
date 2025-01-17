<?php

namespace Akobiabiolaabdbarr\ScrutinApp;

class ElectionSystem
{
    // Méthode pour le vote majoritaire
    public function majoritarian(array $votes): string
    {
        $voteCounts = array_count_values($votes);
        arsort($voteCounts);
        $winner = array_key_first($voteCounts); // Le candidat avec le plus de votes
        return "Option $winner";
    }

    // Méthode pour le vote à deux tours
    public function twoRoundSystem(array $votes, array $candidates): string
    {
        // Premier tour
        $voteCounts = array_count_values($votes);
        arsort($voteCounts);
        $topTwo = array_slice(array_keys($voteCounts), 0, 2);

        if (count($topTwo) < 2) {
            return "Gagnant unique au premier tour : Option {$topTwo[0]}";
        }

        // Second tour
        $secondRoundVotes = array_filter($votes, function ($vote) use ($topTwo) {
            return in_array($vote, $topTwo);
        });

        $secondRoundCounts = array_count_values($secondRoundVotes);
        arsort($secondRoundCounts);
        $winner = array_key_first($secondRoundCounts);
        return "Gagnant au second tour : Option $winner";
    }

    // Méthode pour le vote par points (Borda)
    public function borda(array $votes, array $candidates): string
    {
        $scores = array_fill_keys(array_column($candidates, 'id'), 0);

        foreach ($votes as $vote) {
            $rankings = is_string($vote) ? explode(',', $vote) : $vote; // Gérer les formats chaîne et tableau
            foreach ($rankings as $index => $candidateId) {
                if (isset($scores[$candidateId])) {
                    $scores[$candidateId] += (count($candidates) - $index);
                }
            }
        }

        arsort($scores);
        $winner = array_key_first($scores);
        return "Option $winner avec le score le plus élevé";
    }

    // Méthode pour le vote de Condorcet
    public function condorcet(array $votes, array $candidates): string
    {
        $pairwise = [];

        // Initialiser les comparaisons par paires
        foreach ($candidates as $c1) {
            foreach ($candidates as $c2) {
                if ($c1['id'] !== $c2['id']) {
                    $pairwise[$c1['id']][$c2['id']] = 0;
                }
            }
        }

        // Remplir les comparaisons par paires
        foreach ($votes as $vote) {
            $rankings = is_string($vote) ? explode(',', $vote) : $vote; // Gérer les formats chaîne et tableau
            for ($i = 0; $i < count($rankings); $i++) {
                for ($j = $i + 1; $j < count($rankings); $j++) {
                    $winner = $rankings[$i];
                    $loser = $rankings[$j];

                    if (isset($pairwise[$winner][$loser])) {
                        $pairwise[$winner][$loser]++;
                    }
                }
            }
        }

        // Vérifier les gagnants de Condorcet
        foreach ($pairwise as $candidateId => $scores) {
            $isWinner = true;
            foreach ($scores as $opponentId => $score) {
                if (isset($pairwise[$opponentId][$candidateId]) && $pairwise[$opponentId][$candidateId] > $score) {
                    $isWinner = false;
                    break;
                }
            }
            if ($isWinner) {
                return "Gagnant Condorcet : Option $candidateId";
            }
        }

        return "Pas de gagnant selon Condorcet.";
    }
}