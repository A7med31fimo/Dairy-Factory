<?php

namespace App\Helpers;

/**
 * Arabic text reshaper for dompdf PDF rendering.
 * 
 * Strategy:
 * 1. Shape each word: convert base chars to presentation forms
 * 2. Reverse each word's characters (RTL chars → LTR display)
 * 3. Reverse the entire sentence (word order for RTL)
 */
class ArabicPdf
{
    // [isolated, initial, medial, final]
    private const CHAR_MAP = [
        "\u{0621}" => ["\u{FE80}", "\u{FE80}", "\u{FE80}", "\u{FE80}"], // ء
        "\u{0622}" => ["\u{FE81}", "\u{FE81}", "\u{FE81}", "\u{FE82}"], // آ
        "\u{0623}" => ["\u{FE83}", "\u{FE83}", "\u{FE83}", "\u{FE84}"], // أ
        "\u{0624}" => ["\u{FE85}", "\u{FE85}", "\u{FE85}", "\u{FE86}"], // ؤ
        "\u{0625}" => ["\u{FE87}", "\u{FE87}", "\u{FE87}", "\u{FE88}"], // إ
        "\u{0626}" => ["\u{FE89}", "\u{FE8B}", "\u{FE8C}", "\u{FE8A}"], // ئ
        "\u{0627}" => ["\u{FE8D}", "\u{FE8D}", "\u{FE8D}", "\u{FE8E}"], // ا
        "\u{0628}" => ["\u{FE8F}", "\u{FE91}", "\u{FE92}", "\u{FE90}"], // ب
        "\u{0629}" => ["\u{FE93}", "\u{FE93}", "\u{FE93}", "\u{FE94}"], // ة
        "\u{062A}" => ["\u{FE95}", "\u{FE97}", "\u{FE98}", "\u{FE96}"], // ت
        "\u{062B}" => ["\u{FE99}", "\u{FE9B}", "\u{FE9C}", "\u{FE9A}"], // ث
        "\u{062C}" => ["\u{FE9D}", "\u{FE9F}", "\u{FEA0}", "\u{FE9E}"], // ج
        "\u{062D}" => ["\u{FEA1}", "\u{FEA3}", "\u{FEA4}", "\u{FEA2}"], // ح
        "\u{062E}" => ["\u{FEA5}", "\u{FEA7}", "\u{FEA8}", "\u{FEA6}"], // خ
        "\u{062F}" => ["\u{FEA9}", "\u{FEA9}", "\u{FEA9}", "\u{FEAA}"], // د
        "\u{0630}" => ["\u{FEAB}", "\u{FEAB}", "\u{FEAB}", "\u{FEAC}"], // ذ
        "\u{0631}" => ["\u{FEAD}", "\u{FEAD}", "\u{FEAD}", "\u{FEAE}"], // ر
        "\u{0632}" => ["\u{FEAF}", "\u{FEAF}", "\u{FEAF}", "\u{FEB0}"], // ز
        "\u{0633}" => ["\u{FEB1}", "\u{FEB3}", "\u{FEB4}", "\u{FEB2}"], // س
        "\u{0634}" => ["\u{FEB5}", "\u{FEB7}", "\u{FEB8}", "\u{FEB6}"], // ش
        "\u{0635}" => ["\u{FEB9}", "\u{FEBB}", "\u{FEBC}", "\u{FEBA}"], // ص
        "\u{0636}" => ["\u{FEBD}", "\u{FEBF}", "\u{FEC0}", "\u{FEBE}"], // ض
        "\u{0637}" => ["\u{FEC1}", "\u{FEC3}", "\u{FEC4}", "\u{FEC2}"], // ط
        "\u{0638}" => ["\u{FEC5}", "\u{FEC7}", "\u{FEC8}", "\u{FEC6}"], // ظ
        "\u{0639}" => ["\u{FEC9}", "\u{FECB}", "\u{FECC}", "\u{FECA}"], // ع
        "\u{063A}" => ["\u{FECD}", "\u{FECF}", "\u{FED0}", "\u{FECE}"], // غ
        "\u{0641}" => ["\u{FED1}", "\u{FED3}", "\u{FED4}", "\u{FED2}"], // ف
        "\u{0642}" => ["\u{FED5}", "\u{FED7}", "\u{FED8}", "\u{FED6}"], // ق
        "\u{0643}" => ["\u{FED9}", "\u{FEDB}", "\u{FEDC}", "\u{FEDA}"], // ك
        "\u{0644}" => ["\u{FEDD}", "\u{FEDF}", "\u{FEE0}", "\u{FEDE}"], // ل
        "\u{0645}" => ["\u{FEE1}", "\u{FEE3}", "\u{FEE4}", "\u{FEE2}"], // م
        "\u{0646}" => ["\u{FEE5}", "\u{FEE7}", "\u{FEE8}", "\u{FEE6}"], // ن
        "\u{0647}" => ["\u{FEE9}", "\u{FEEB}", "\u{FEEC}", "\u{FEEA}"], // ه
        "\u{0648}" => ["\u{FEED}", "\u{FEED}", "\u{FEED}", "\u{FEEE}"], // و
        "\u{0649}" => ["\u{FEEF}", "\u{FEEF}", "\u{FEEF}", "\u{FEF0}"], // ى
        "\u{064A}" => ["\u{FEF1}", "\u{FEF3}", "\u{FEF4}", "\u{FEF2}"], // ي
        "\u{0671}" => ["\u{FB50}", "\u{FB50}", "\u{FB50}", "\u{FB51}"], // ٱ
    ];

    private const RIGHT_ONLY = [
        "\u{0621}", "\u{0622}", "\u{0623}", "\u{0624}", "\u{0625}",
        "\u{0627}", "\u{062F}", "\u{0630}", "\u{0631}", "\u{0632}",
        "\u{0648}", "\u{0649}", "\u{0671}",
    ];

    private const LAM_ALEF = [
        "\u{0622}" => ["\u{FEF5}", "\u{FEF6}"],
        "\u{0623}" => ["\u{FEF7}", "\u{FEF8}"],
        "\u{0625}" => ["\u{FEF9}", "\u{FEFA}"],
        "\u{0627}" => ["\u{FEFB}", "\u{FEFC}"],
    ];

    private const DIACRITICS = [
        "\u{064B}", "\u{064C}", "\u{064D}", "\u{064E}", "\u{064F}",
        "\u{0650}", "\u{0651}", "\u{0652}", "\u{0640}",
    ];

    public static function reshape(string $text): string
    {
        if ($text === '') return '';
        $lines = explode("\n", $text);
        return implode("\n", array_map([self::class, 'reshapeLine'], $lines));
    }

    private static function reshapeLine(string $line): string
    {
        if ($line === '') return '';

        // Process mixed Arabic/Latin runs
        $output = '';
        $buffer = '';
        $isArabic = null;

        $chars = mb_str_split($line, 1, 'UTF-8');
        foreach ($chars as $ch) {
            $charIsArabic = self::isArabicChar($ch);
            
            if ($isArabic === null) {
                $isArabic = $charIsArabic;
                $buffer = $ch;
            } elseif ($charIsArabic === $isArabic) {
                $buffer .= $ch;
            } else {
                $output .= $isArabic ? self::processArabicRun($buffer) : $buffer;
                $buffer = $ch;
                $isArabic = $charIsArabic;
            }
        }
        
        if ($buffer !== '') {
            $output .= $isArabic ? self::processArabicRun($buffer) : $buffer;
        }

        return $output;
    }

    private static function isArabicChar(string $ch): bool
    {
        $cp = mb_ord($ch, 'UTF-8');
        return ($cp >= 0x0600 && $cp <= 0x06FF)
            || $ch === '،' || $ch === '؟' || $ch === '؛'
            || $ch === ' '; // Space is neutral, include in Arabic runs
    }

    private static function processArabicRun(string $text): string
    {
        // Strip diacritics
        $clean = str_replace(self::DIACRITICS, '', $text);
        
        // Tokenize: split by space but keep spaces as tokens
        $tokens = [];
        $current = '';
        $chars = mb_str_split($clean, 1, 'UTF-8');
        
        foreach ($chars as $ch) {
            if ($ch === ' ') {
                if ($current !== '') {
                    $tokens[] = ['word', $current];
                    $current = '';
                }
                $tokens[] = ['space', ' '];
            } else {
                $current .= $ch;
            }
        }
        if ($current !== '') {
            $tokens[] = ['word', $current];
        }

        // Shape each word (this reverses chars within the word)
        $shaped = [];
        foreach ($tokens as $token) {
            [$type, $val] = $token;
            $shaped[] = $type === 'word' ? self::reshapeWord($val) : $val;
        }

        // CRITICAL: Reverse the entire token array for RTL display in LTR dompdf
        // This gives us: [shaped_word4, space, shaped_word3, space, shaped_word2, ...]
        return implode('', array_reverse($shaped));
    }

    private static function reshapeWord(string $word): string
    {
        $chars = mb_str_split($word, 1, 'UTF-8');
        $n = count($chars);
        $out = [];
        $i = 0;

        while ($i < $n) {
            $ch = $chars[$i];

            if (!isset(self::CHAR_MAP[$ch])) {
                $out[] = $ch;
                $i++;
                continue;
            }

            // Lam-Alef ligature
            if ($ch === "\u{0644}" && isset($chars[$i + 1]) && isset(self::LAM_ALEF[$chars[$i + 1]])) {
                $prevConnects = $i > 0 && self::canConnectLeft($chars[$i - 1]);
                $pair = self::LAM_ALEF[$chars[$i + 1]];
                $out[] = $prevConnects ? $pair[1] : $pair[0];
                $i += 2;
                continue;
            }

            // Determine form
            $prev = $chars[$i - 1] ?? null;
            $next = $chars[$i + 1] ?? null;

            $prevGives = $prev !== null && self::canConnectLeft($prev);
            $weGive = !in_array($ch, self::RIGHT_ONLY, true);
            $nextTakes = $next !== null && isset(self::CHAR_MAP[$next]) && $weGive;

            if ($prevGives && $nextTakes) {
                $form = 2;
            } elseif ($prevGives) {
                $form = 3;
            } elseif ($nextTakes) {
                $form = 1;
            } else {
                $form = 0;
            }

            $out[] = self::CHAR_MAP[$ch][$form];
            $i++;
        }

        // Reverse the word's characters
        return implode('', array_reverse($out));
    }

    private static function canConnectLeft(string $ch): bool
    {
        return isset(self::CHAR_MAP[$ch]) && !in_array($ch, self::RIGHT_ONLY, true);
    }
}
