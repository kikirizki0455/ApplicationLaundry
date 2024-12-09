<?php

namespace App\Libraries;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\Validation;


class RuanganHelper
{
    protected $db, $validation;

    public function __construct(ConnectionInterface $db, ?Validation $validation = null)
    {
        $this->db = $db;
        $this->validation = $validation ?? \Config\Services::validation();
    }

    public function normalize($name)
    {
        $name = preg_replace('/[^a-zA-z\s]/u', '', $name);
        $name = preg_replace('/\s+/', '', $name);
        $name = preg_replace('/([a-zA-Z])\1{2,}/u', '$1$1', $name);
        return ucwords(strtolower(trim($name)));
    }
    public function checkDuplicate($name)
    {
        $normalized_name = $this->normalize($name);
        $similar_items = $this->db->table('ruangan')
            ->select('nama_ruangan')
            ->get()
            ->getResult();

        foreach ($similar_items as $item) {
            $similarity = $this->advancedSimilarity($normalized_name, $item->nama_ruangan);

            // Tingkatkan ambang batas kemiripan untuk mendeteksi typo
            if ($similarity > 0.7) {
                return true;
            }
        }

        return false;
    }


    private function advancedSimilarity($str1, $str2)
    {
        // Kombinasi beberapa metode perhitungan kemiripan
        $levenshteinSimilarity = $this->calculateLevenshteinSimilarity($str1, $str2);
        $soundexSimilarity = $this->calculateSoundexSimilarity($str1, $str2);
        $metaphoneSimilarity = $this->calculateMetaphoneSimilarity($str1, $str2);

        // Bobot rata-rata dari metode perhitungan
        return ($levenshteinSimilarity * 0.4 +
            $soundexSimilarity * 0.3 +
            $metaphoneSimilarity * 0.3);
    }
    private function calculateLevenshteinSimilarity($str1, $str2)
    {
        $len1 = mb_strlen($str1);
        $len2 = mb_strlen($str2);
        $maxLen = max($len1, $len2);
        $distance = levenshtein($str1, $str2);
        return 1 - ($distance / $maxLen);
    }

    private function calculateSoundexSimilarity($str1, $str2)
    {
        $soundex1 = soundex($str1);
        $soundex2 = soundex($str2);
        return $soundex1 === $soundex2 ? 1.0 : 0.0;
    }

    private function calculateMetaphoneSimilarity($str1, $str2)
    {
        // Implementasi sederhana metaphone
        $metaphone1 = metaphone($str1);
        $metaphone2 = metaphone($str2);
        return similar_text($metaphone1, $metaphone2) / max(strlen($metaphone1), strlen($metaphone2));
    }
    public function validateRuanganData($data)
    {
        $rules = [
            'nama_ruangan' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama Ruangan harus diisi.',
                    'min_length' => 'Nama Ruangan minimal 3 karakter.',
                    'max_length' => 'Nama Ruangan maksimal 100 karakter.'
                ]

            ]
        ];

        if (!$this->validation->setRules($rules)->run($data)) {
            return $this->validation->getErrors();
        }

        return null;
    }
}
