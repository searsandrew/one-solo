<?php

namespace App;

enum PositionGroup: string
{
    case Goalkeeper = 'goalkeeper';
    case Defender = 'defender';
    case Midfielder = 'midfielder';
    case Forward = 'forward';

    public function label(): string
    {
        return match ($this) {
            self::Goalkeeper => 'Goalie',
            self::Defender => 'Defender',
            self::Midfielder => 'Midfielder',
            self::Forward => 'Forward',
        };
    }

    public function pluralLabel(): string
    {
        return match ($this) {
            self::Goalkeeper => 'Goalies',
            self::Defender => 'Defenders',
            self::Midfielder => 'Midfielders',
            self::Forward => 'Forwards',
        };
    }

    public function countColumn(): string
    {
        return match ($this) {
            self::Goalkeeper => 'goalkeepers_count',
            self::Defender => 'defenders_count',
            self::Midfielder => 'midfielders_count',
            self::Forward => 'forwards_count',
        };
    }

    /**
     * @return array<int, self>
     */
    public static function ordered(): array
    {
        return [
            self::Goalkeeper,
            self::Defender,
            self::Midfielder,
            self::Forward,
        ];
    }
}
