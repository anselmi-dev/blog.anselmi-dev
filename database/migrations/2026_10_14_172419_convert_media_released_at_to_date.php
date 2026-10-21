<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * La fecha de las fotos pasa de texto libre ("12 mar 2025") a una fecha real
 * (released_on), para elegirla con calendario. Los textos que no se pueden
 * leer como fecha se avisan por consola y quedan vacíos.
 */
return new class extends Migration
{
    private const array MONTHS = [
        'ene' => 1, 'feb' => 2, 'mar' => 3, 'abr' => 4, 'may' => 5, 'jun' => 6,
        'jul' => 7, 'ago' => 8, 'sep' => 9, 'set' => 9, 'oct' => 10, 'nov' => 11, 'dic' => 12,
        'jan' => 1, 'apr' => 4, 'aug' => 8, 'dec' => 12,
    ];

    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->date('released_on')->nullable()->after('location');
        });

        DB::table('media')->whereNotNull('released_at')->orderBy('id')->each(function (object $media): void {
            $date = $this->parse((string) $media->released_at);

            if ($date === null) {
                fwrite(STDERR, "  media #{$media->id}: no se pudo leer la fecha \"{$media->released_at}\", queda vacía.\n");

                return;
            }

            DB::table('media')->where('id', $media->id)->update(['released_on' => $date->toDateString()]);
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('released_at');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('released_at')->nullable()->after('location');
        });

        DB::table('media')->whereNotNull('released_on')->orderBy('id')->each(function (object $media): void {
            $date = Carbon::parse($media->released_on);
            $month = array_search($date->month, self::MONTHS, true);

            DB::table('media')->where('id', $media->id)->update([
                'released_at' => sprintf('%02d %s %d', $date->day, $month, $date->year),
            ]);
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('released_on');
        });
    }

    /**
     * "12 mar 2025", "12 de marzo de 2025", "2025-03-12", "12/03/2025"…
     */
    private function parse(string $value): ?Carbon
    {
        $value = trim(mb_strtolower($value));

        if ($value === '') {
            return null;
        }

        try {
            if (preg_match('/^(\d{1,2})\s+(?:de\s+)?([a-zñ]{3})[a-zñ]*\.?\s+(?:de\s+)?(\d{4})$/u', $value, $matches) === 1) {
                return isset(self::MONTHS[$matches[2]])
                    ? Carbon::createSafe((int) $matches[3], self::MONTHS[$matches[2]], (int) $matches[1])
                    : null;
            }

            if (preg_match('/^(\d{1,2})[\/.-](\d{1,2})[\/.-](\d{4})$/', $value, $matches) === 1) {
                return Carbon::createSafe((int) $matches[3], (int) $matches[2], (int) $matches[1]);
            }

            return Carbon::parse($value);
        } catch (Throwable) {
            return null;
        }
    }
};
