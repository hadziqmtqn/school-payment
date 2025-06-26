<?php

namespace App\Jobs\SendMessage;

use App\Mail\NewStudentMail;
use App\Models\Student;
use App\Traits\MessageReplaceTrait;
use App\Traits\MessageTrait;
use App\Traits\SendWhatsappMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewStudentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendWhatsappMessage, MessageTrait, MessageReplaceTrait;

    protected Student $student;
    protected mixed $password;

    /**
     * @param Student $student
     * @param mixed $password
     */
    public function __construct(Student $student, mixed $password)
    {
        $this->student = $student;
        $this->password = $password;
    }

    public function handle(): void
    {
        $this->student->load([
            'user',
            'studentLevel' => function ($query) {
                $query->whereHas('schoolYear', fn($query) => $query->active());
            },
            'studentLevel.classLevel',
            'studentLevel.subClassLevel'
        ]);

        $placeholders = [
            "{nama_aplikasi}" => $this->app()->name,
            "{nama_siswa}" => $this->student->user?->name,
            "{kelas}" => $this->student->studentLevel?->classLevel?->name,
            "{sub_kelas}" => $this->student->studentLevel?->subClassLevel?->name,
            "{password}" => $this->password
        ];

        if ($this->message('registrasi-akun-baru', 'siswa')) {
            // notifikasi menggunakan email
            if ($this->app()->notification_method == 'email') {
                Mail::to($this->student->user?->email)
                    ->send(new NewStudentMail([
                        'message' => $this->replacePlaceholders($this->message('registrasi-akun-baru', 'siswa')->message, $placeholders)
                    ]));
            }

            // notifikasi menggunakan whatsapp
            if ($this->app()->notification_method == 'whatsapp') {
                $this->sendWhatsappMessage([
                    'phone' => $this->student->whatsapp_number,
                    'message' => $this->replacePlaceholders($this->message('registrasi-akun-baru', 'siswa')->message, $placeholders)
                ]);
            }
        }
    }
}
