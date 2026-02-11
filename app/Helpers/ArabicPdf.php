<?php

namespace App\Helpers;

/**
 * Arabic text reshaper for dompdf PDF rendering.
 *
 * dompdf does not implement the Unicode Bidirectional Algorithm or Arabic
 * text shaping. This class converts Arabic base characters (U+0600–U+06FF)
 * into their correct contextual presentation forms (U+FE70–U+FEFF), applies
 * Lam-Alef ligatures, and reverses the character order so dompdf renders
 * the text correctly in its LTR rendering pipeline.
 *
 * Usage:
 *   $shaped = ArabicPdf::reshape('مصنع الألبان');
 *   // Returns reversed presentation-form string ready for dompdf
 */
class ArabicPdf
{
    // [isolated, initial, medial, final]
    private const CHAR_MAP = [
        "\u{0621}" => ["\u{FE80}", "\u{FE80}", "\u{FE80}", "\u{FE80}"], // ء hamza
        "\u{0622}" => ["\u{FE81}", "\u{FE81}", "\u{FE81}", "\u{FE82}"], // آ alef madda
        "\u{0623}" => ["\u{FE83}", "\u{FE83}", "\u{FE83}", "\u{FE84}"], // أ alef hamza above
        "\u{0624}" => ["\u{FE85}", "\u{FE85}", "\u{FE85}", "\u{FE86}"], // ؤ waw hamza
        "\u{0625}" => ["\u{FE87}", "\u{FE87}", "\u{FE87}", "\u{FE88}"], // إ alef hamza below
        "\u{0626}" => ["\u{FE89}", "\u{FE8B}", "\u{FE8C}", "\u{FE8A}"], // ئ yeh hamza
        "\u{0627}" => ["\u{FE8D}", "\u{FE8D}", "\u{FE8D}", "\u{FE8E}"], // ا alef
        "\u{0628}" => ["\u{FE8F}", "\u{FE91}", "\u{FE92}", "\u{FE90}"], // ب ba
        "\u{0629}" => ["\u{FE93}", "\u{FE93}", "\u{FE93}", "\u{FE94}"], // ة taa marbuta
        "\u{062A}" => ["\u{FE95}", "\u{FE97}", "\u{FE98}", "\u{FE96}"], // ت ta
        "\u{062B}" => ["\u{FE99}", "\u{FE9B}", "\u{FE9C}", "\u{FE9A}"], // ث tha
        "\u{062C}" => ["\u{FE9D}", "\u{FE9F}", "\u{FEA0}", "\u{FE9E}"], // ج jeem
        "\u{062D}" => ["\u{FEA1}", "\u{FEA3}", "\u{FEA4}", "\u{FEA2}"], // ح hah
        "\u{062E}" => ["\u{FEA5}", "\u{FEA7}", "\u{FEA8}", "\u{FEA6}"], // خ khah
        "\u{062F}" => ["\u{FEA9}", "\u{FEA9}", "\u{FEA9}", "\u{FEAA}"], // د dal
        "\u{0630}" => ["\u{FEAB}", "\u{FEAB}", "\u{FEAB}", "\u{FEAC}"], // ذ thal
        "\u{0631}" => ["\u{FEAD}", "\u{FEAD}", "\u{FEAD}", "\u{FEAE}"], // ر ra
        "\u{0632}" => ["\u{FEAF}", "\u{FEAF}", "\u{FEAF}", "\u{FEB0}"], // ز zain
        "\u{0633}" => ["\u{FEB1}", "\u{FEB3}", "\u{FEB4}", "\u{FEB2}"], // س seen
        "\u{0634}" => ["\u{FEB5}", "\u{FEB7}", "\u{FEB8}", "\u{FEB6}"], // ش sheen
        "\u{0635}" => ["\u{FEB9}", "\u{FEBB}", "\u{FEBC}", "\u{FEBA}"], // ص sad
        "\u{0636}" => ["\u{FEBD}", "\u{FEBF}", "\u{FEC0}", "\u{FEBE}"], // ض dad
        "\u{0637}" => ["\u{FEC1}", "\u{FEC3}", "\u{FEC4}", "\u{FEC2}"], // ط tah
        "\u{0638}" => ["\u{FEC5}", "\u{FEC7}", "\u{FEC8}", "\u{FEC6}"], // ظ zah
        "\u{0639}" => ["\u{FEC9}", "\u{FECB}", "\u{FECC}", "\u{FECA}"], // ع ain
        "\u{063A}" => ["\u{FECD}", "\u{FECF}", "\u{FED0}", "\u{FECE}"], // غ ghain
        "\u{0641}" => ["\u{FED1}", "\u{FED3}", "\u{FED4}", "\u{FED2}"], // ف fa
        "\u{0642}" => ["\u{FED5}", "\u{FED7}", "\u{FED8}", "\u{FED6}"], // ق qaf
        "\u{0643}" => ["\u{FED9}", "\u{FEDB}", "\u{FEDC}", "\u{FEDA}"], // ك kaf
        "\u{0644}" => ["\u{FEDD}", "\u{FEDF}", "\u{FEE0}", "\u{FEDE}"], // ل lam
        "\u{0645}" => ["\u{FEE1}", "\u{FEE3}", "\u{FEE4}", "\u{FEE2}"], // م meem
        "\u{0646}" => ["\u{FEE5}", "\u{FEE7}", "\u{FEE8}", "\u{FEE6}"], // ن noon
        "\u{0647}" => ["\u{FEE9}", "\u{FEEB}", "\u{FEEC}", "\u{FEEA}"], // ه ha
        "\u{0648}" => ["\u{FEED}", "\u{FEED}", "\u{FEED}", "\u{FEEE}"], // و waw
        "\u{0649}" => ["\u{FEEF}", "\u{FEEF}", "\u{FEEF}", "\u{FEF0}"], // ى alef maqsura
        "\u{064A}" => ["\u{FEF1}", "\u{FEF3}", "\u{FEF4}", "\u{FEF2}"], // ي yeh
        // Extended Arabic letters (common in Egyptian Arabic)
        "\u{0671}" => ["\u{FB50}", "\u{FB50}", "\u{FB50}", "\u{FB51}"], // ٱ alef wasla
        "\u{0679}" => ["\u{FB66}", "\u{FB68}", "\u{FB69}", "\u{FB67}"], // ٹ
        "\u{067E}" => ["\u{FB56}", "\u{FB58}", "\u{FB59}", "\u{FB57}"], // پ
        "\u{0686}" => ["\u{FB7A}", "\u{FB7C}", "\u{FB7D}", "\u{FB7B}"], // چ
        "\u{0698}" => ["\u{FB8A}", "\u{FB8A}", "\u{FB8A}", "\u{FB8B}"], // ژ
        "\u{06A9}" => ["\u{FB8E}", "\u{FB90}", "\u{FB91}", "\u{FB8F}"], // ک
        "\u{06AF}" => ["\u{FB92}", "\u{FB94}", "\u{FB95}", "\u{FB93}"], // گ
        "\u{06CC}" => ["\u{FBFC}", "\u{FBFE}", "\u{FBFF}", "\u{FBFD}"], // ی
    ];

    /**
     * Characters that are right-joiners only — they cannot connect to the
     * following character (no initial or medial forms).
     * They CAN still receive a connection from a preceding character (final form).
     */
    private const RIGHT_ONLY = [
        "\u{0621}", "\u{0622}", "\u{0623}", "\u{0624}", "\u{0625}",
        "\u{0627}", "\u{062F}", "\u{0630}", "\u{0631}", "\u{0632}",
        "\u{0648}", "\u{0649}", "\u{0671}", "\u{0698}",
    ];

    /** Lam + Alef ligatures: [isolated-lam-alef, final-lam-alef] at base codepoint */
    private const LAM_ALEF = [
        "\u{0622}" => ["\u{FEF5}", "\u{FEF6}"], // لآ
        "\u{0623}" => ["\u{FEF7}", "\u{FEF8}"], // لأ
        "\u{0625}" => ["\u{FEF9}", "\u{FEFA}"], // لإ
        "\u{0627}" => ["\u{FEFB}", "\u{FEFC}"], // لا
    ];

    /** Diacritics and tatweel to strip before reshaping */
    private const DIACRITICS = [
        "\u{064B}", "\u{064C}", "\u{064D}", "\u{064E}", "\u{064F}",
        "\u{0650}", "\u{0651}", "\u{0652}", "\u{0653}", "\u{0654}",
        "\u{0655}", "\u{0656}", "\u{0670}", "\u{0640}",
    ];

    /**
     * Reshape Arabic text for dompdf rendering.
     * Handles multi-line strings and mixed Arabic/non-Arabic content.
     */
    public static function reshape(string $text): string
    {
        if ($text === '') return '';

        $lines = explode("\n", $text);
        return implode("\n", array_map([self::class, 'reshapeLine'], $lines));
    }

    private static function reshapeLine(string $line): string
    {
        if ($line === '') return '';

        $output   = '';
        $arabicBuf = '';

        $chars = mb_str_split($line, 1, 'UTF-8');

        foreach ($chars as $ch) {
            if (self::isArabicChar($ch)) {
                $arabicBuf .= $ch;
            } else {
                if ($arabicBuf !== '') {
                    $output   .= self::processRun($arabicBuf);
                    $arabicBuf = '';
                }
                $output .= $ch;
            }
        }

        if ($arabicBuf !== '') {
            $output .= self::processRun($arabicBuf);
        }

        return $output;
    }

    private static function isArabicChar(string $ch): bool
    {
        $cp = mb_ord($ch, 'UTF-8');
        return ($cp >= 0x0600 && $cp <= 0x06FF)
            || ($cp >= 0xFB50 && $cp <= 0xFDFF)
            || ($cp >= 0xFE70 && $cp <= 0xFEFF)
            || $ch === "\u{200C}" || $ch === "\u{200D}" // ZWNJ / ZWJ
            || $ch === '،' || $ch === '؟' || $ch === '؛' || $ch === '«' || $ch === '»';
    }

    private static function processRun(string $text): string
    {
        // Strip diacritics
        $clean = str_replace(self::DIACRITICS, '', $text);
        $chars = mb_str_split($clean, 1, 'UTF-8');

        // Tokenise into words (split on space/punctuation preserved as tokens)
        $words   = [];
        $current = '';
        foreach ($chars as $ch) {
            if ($ch === ' ') {
                if ($current !== '') {
                    $words[] = ['word', $current];
                    $current = '';
                }
                $words[] = ['space', ' '];
            } elseif (self::isPunctuation($ch)) {
                if ($current !== '') {
                    $words[] = ['word', $current];
                    $current = '';
                }
                $words[] = ['punct', $ch];
            } else {
                $current .= $ch;
            }
        }
        if ($current !== '') {
            $words[] = ['word', $current];
        }

        // Reshape each word
        $shaped = array_map(function ($token) {
            [$type, $val] = $token;
            return $type === 'word' ? self::reshapeWord($val) : $val;
        }, $words);

        // Reverse word/token order for RTL display in dompdf's LTR pipeline
        return implode('', array_reverse($shaped));
    }

    private static function reshapeWord(string $word): string
    {
        $chars = mb_str_split($word, 1, 'UTF-8');
        $n     = count($chars);
        $out   = [];
        $i     = 0;

        while ($i < $n) {
            $ch = $chars[$i];

            if (!isset(self::CHAR_MAP[$ch])) {
                $out[] = $ch;
                $i++;
                continue;
            }

            // Lam-Alef ligature check
            if ($ch === "\u{0644}" && isset($chars[$i + 1]) && isset(self::LAM_ALEF[$chars[$i + 1]])) {
                $lamConnected = $i > 0 && self::canConnectLeft($chars[$i - 1]);
                $pair = self::LAM_ALEF[$chars[$i + 1]];
                $out[] = $lamConnected ? $pair[1] : $pair[0];
                $i += 2;
                continue;
            }

            // Determine contextual form
            $prev = $chars[$i - 1] ?? null;
            $next = $chars[$i + 1] ?? null;

            // Previous char connects TO us (we can be final/medial) if:
            // prev is an Arabic letter AND prev can connect to its left side
            $prevGives = $prev !== null && self::canConnectLeft($prev);

            // We connect TO next (next can be final/medial) if:
            // we are not a right-only char AND next is an Arabic letter
            $weGive   = !in_array($ch, self::RIGHT_ONLY, true);
            $nextTakes = $next !== null && isset(self::CHAR_MAP[$next]) && $weGive;

            if ($prevGives && $nextTakes) {
                $form = 2; // medial
            } elseif ($prevGives) {
                $form = 3; // final  ← fixed: ALL Arabic letters have a final form
            } elseif ($nextTakes) {
                $form = 1; // initial
            } else {
                $form = 0; // isolated
            }

            $out[] = self::CHAR_MAP[$ch][$form];
            $i++;
        }

        // Reverse the shaped characters within the word (RTL → LTR for dompdf)
        return implode('', array_reverse($out));
    }

    /** Can this character connect to the character on its LEFT (i.e., the following char in visual RTL)? */
    private static function canConnectLeft(string $ch): bool
    {
        return isset(self::CHAR_MAP[$ch]) && !in_array($ch, self::RIGHT_ONLY, true);
    }

    private static function isPunctuation(string $ch): bool
    {
        return in_array($ch, ['،', '؟', '؛', '!', ':', '-', '(', ')', '«', '»'], true);
    }
}
