<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AnonymousUser;
use App\Models\MoodLog;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $moods = ['Senang', 'Sedih', 'Marah', 'Cemas', 'Netral'];
        
        // Buat 10 Karyawan (Anonymous User)
        for ($i = 1; $i <= 10; $i++) {
            $user = AnonymousUser::create([
                'unique_code' => sprintf('%04d', rand(0, 9999)),
                'created_at' => now()->subDays(rand(1, 14)),
                'updated_at' => now(),
            ]);

            // Buat Mood Logs untuk 7 hari terakhir
            for ($daysBack = 7; $daysBack >= 0; $daysBack--) {
                if (rand(1, 100) <= 70) { 
                    $randomMood = $moods[array_rand($moods)];
                    $emotionLabel = strtolower($randomMood);
                    if($emotionLabel == 'senang') $emotionLabel = 'happy';
                    if($emotionLabel == 'sedih') $emotionLabel = 'sad';
                    if($emotionLabel == 'marah') $emotionLabel = 'stress';
                    if($emotionLabel == 'cemas') $emotionLabel = 'stress';
                    if($emotionLabel == 'netral') $emotionLabel = 'neutral';
                    
                    MoodLog::create([
                        'anonymous_user_id' => $user->id,
                        'mood' => $randomMood,
                        'emotion_label' => $emotionLabel,
                        'confidence_score' => rand(700, 999) / 10,
                        'created_at' => Carbon::now()->subDays($daysBack)->addHours(rand(8, 17)),
                        'updated_at' => Carbon::now()->subDays($daysBack)->addHours(rand(8, 17)),
                    ]);
                }
            }

            // Buat Chat Messages simulasi
            $messageCount = rand(2, 6);
            for ($m = 0; $m < $messageCount; $m++) {
                $isFromEmployee = (rand(1, 100) <= 60);
                
                $messageData = [
                    'anonymous_user_id' => $user->id,
                    'sender' => $isFromEmployee ? 'employee' : 'admin',
                    'is_admin' => !$isFromEmployee,
                    'is_read' => $isFromEmployee ? (rand(1, 100) > 30) : true,
                    'created_at' => Carbon::now()->subDays(rand(0, 3))->addHours(rand(8, 17)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 3))->addHours(rand(8, 17)),
                ];
                
                if ($isFromEmployee) {
                    $employeeMsg = [
                        'Pekerjaan hari ini sangat menumpuk dan membuat saya stress.',
                        'Saya merasa cukup bahagia dengan pencapaian tim minggu ini.',
                        'Apakah ada sesi konseling yang tersedia besok pagi?',
                        'Saya cemas dengan deadline project yang semakin dekat.',
                        'Hari ini berjalan biasa saja, tidak ada yang spesial.'
                    ];
                    $messageData['message'] = $employeeMsg[array_rand($employeeMsg)];
                    
                    if (str_contains($messageData['message'], 'stress') || str_contains($messageData['message'], 'cemas')) {
                        $messageData['emotion'] = 'stress';
                    } elseif (str_contains($messageData['message'], 'bahagia')) {
                        $messageData['emotion'] = 'happy';
                    } else {
                        $messageData['emotion'] = 'neutral';
                    }
                    $messageData['confidence'] = rand(750, 999) / 10;
                } else {
                    $adminMsg = [
                        'Halo, terima kasih sudah menghubungi. Apa ada yang bisa saya bantu lebih lanjut?',
                        'Saya mengerti perasaan Anda. Mari kita jadwalkan sesi konseling ya.',
                        'Tetap semangat! Jangan ragu untuk bercerita di sini.',
                        'Silakan beristirahat sejenak dari pekerjaan jika merasa terlalu penat.'
                    ];
                    $messageData['message'] = $adminMsg[array_rand($adminMsg)];
                    $messageData['emotion'] = null;
                    $messageData['confidence'] = null;
                }
                
                Message::create($messageData);
            }
        }
    }
}
