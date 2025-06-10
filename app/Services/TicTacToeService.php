<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Move;

class TicTacToeService
{
    const WINNING_COMBINATIONS = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6],
    ];

    public function checkForWinner(Game $game): ?int
    {
        $moves = $game->moves()->orderBy('turn_number')->get()->groupBy('player_id');

        foreach ($moves as $playerId => $playerMoves) {
            $positions = $playerMoves->pluck('position')->all();

            foreach (self::WINNING_COMBINATIONS as $combo) {
                if (count(array_intersect($combo, $positions)) === 3) {
                    return $playerId;
                }
            }
        }

        return null;
    }

    public function isDraw(Game $game): bool
    {
        return $game->moves()->count() >= 9 && !$this->checkForWinner($game);
    }

    public function isPlayerTurn(Game $game, int $playerId): bool
    {
        $movesCount = $game->moves()->count();
        $isEvenTurn = $movesCount % 2 === 0;

        return ($isEvenTurn && $game->player_x_id === $playerId) ||
               (!$isEvenTurn && $game->player_o_id === $playerId);
    }
}
