<?php

namespace App\Games\TicTactToe\Livewire;

use Livewire\Component;

class TicTacToe extends Component
{
    private const array LINES = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6],
    ];

    /** @var array<int, string|null> */
    public array $board = [];

    public string $status = 'playing';

    public ?int $winningLineKey = null;

    public bool $waitingForAi = false;

    /** Coordenadas en viewBox 0–100 para la línea ganadora */
    private const array LINE_SVG = [
        [16.67, 16.67, 83.33, 16.67],
        [16.67, 50, 83.33, 50],
        [16.67, 83.33, 83.33, 83.33],
        [16.67, 16.67, 16.67, 83.33],
        [50, 16.67, 50, 83.33],
        [83.33, 16.67, 83.33, 83.33],
        [16.67, 16.67, 83.33, 83.33],
        [83.33, 16.67, 16.67, 83.33],
    ];

    public function mount(): void
    {
        $this->resetBoard();
    }

    public function resetBoard(): void
    {
        $this->board = array_fill(0, 9, null);
        $this->status = 'playing';
        $this->winningLineKey = null;
        $this->waitingForAi = false;
    }

    public function play(int $index): void
    {
        if ($this->status !== 'playing' || $this->waitingForAi) {
            return;
        }
        if ($index < 0 || $index > 8 || $this->board[$index] !== null) {
            return;
        }

        $this->board[$index] = 'X';

        if ($this->applyEndState()) {
            return;
        }

        $this->waitingForAi = true;
        $this->js('setTimeout(() => $wire.aiRespond(), 520)');
    }

    public function aiRespond(): void
    {
        if ($this->status !== 'playing' || ! $this->waitingForAi) {
            return;
        }

        $this->waitingForAi = false;
        $this->aiMove();
        $this->applyEndState();
    }

    protected function aiMove(): void
    {
        $bestScore = -PHP_INT_MAX;
        $bestMove = null;

        for ($i = 0; $i < 9; $i++) {
            if ($this->board[$i] !== null) {
                continue;
            }
            $this->board[$i] = 'O';
            $score = $this->minimax($this->board, false);
            $this->board[$i] = null;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMove = $i;
            }
        }

        if ($bestMove !== null) {
            $this->board[$bestMove] = 'O';
        }
    }

    protected function minimax(array $board, bool $isAiTurn): int
    {
        $terminal = $this->scoreTerminal($board);
        if ($terminal !== null) {
            return $terminal;
        }

        if ($isAiTurn) {
            $best = -PHP_INT_MAX;
            for ($i = 0; $i < 9; $i++) {
                if ($board[$i] !== null) {
                    continue;
                }
                $board[$i] = 'O';
                $best = max($best, $this->minimax($board, false));
                $board[$i] = null;
            }

            return $best;
        }

        $best = PHP_INT_MAX;
        for ($i = 0; $i < 9; $i++) {
            if ($board[$i] !== null) {
                continue;
            }
            $board[$i] = 'X';
            $best = min($best, $this->minimax($board, true));
            $board[$i] = null;
        }

        return $best;
    }

    protected function scoreTerminal(array $board): ?int
    {
        foreach (self::LINES as $line) {
            $c = $board[$line[0]];
            if ($c !== null && $c === $board[$line[1]] && $c === $board[$line[2]]) {
                return $c === 'O' ? 1 : -1;
            }
        }

        if (! in_array(null, $board, true)) {
            return 0;
        }

        return null;
    }

    protected function applyEndState(): bool
    {
        foreach (self::LINES as $key => $line) {
            $c = $this->board[$line[0]];
            if ($c !== null && $c === $this->board[$line[1]] && $c === $this->board[$line[2]]) {
                $this->status = $c === 'X' ? 'won_x' : 'won_o';
                $this->winningLineKey = $key;

                return true;
            }
        }

        if (! in_array(null, $this->board, true)) {
            $this->status = 'draw';

            return true;
        }

        return false;
    }

    public function lineSvgCoords(): ?array
    {
        if ($this->winningLineKey === null) {
            return null;
        }

        return self::LINE_SVG[$this->winningLineKey] ?? null;
    }

    public function render()
    {
        return view('games::tic-tac-toe');
    }
}
