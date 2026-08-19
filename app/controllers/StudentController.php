<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller
{
    private function student()
    {
        return [
            'student_id' => 'MCC2024-06104',
            'name'       => 'Ken Jerry',
            'course'     => 'BS Information Technology',
            'year'       => '3rd Year',
            'section'    => 'BSIT-3F6',
            'email'      => 'mendoza.kenjerrya@minsu.edu.ph',
            'address'    => 'Baco, Philippines',
            'contact'    => '0994-086-9635',
            'description'=> 'A dedicated IT student who enjoys learning web development, building clean interfaces, and improving practical coding skills.',
            'skills'     => ['PHP', 'HTML', 'CSS', 'Networking', 'Yearner'],
            'hobbies'    => ['Rides', 'Sleeping', 'Online Gaming', 'Watching Movies', 'Listening to Music']
        ];
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (($_GET['permission'] ?? '') === 'yes') {
            $_SESSION['student_access'] = 'student-portal-cleared';
            redirect('student', false, false);
            return;
        }

        if (($_GET['permission'] ?? '') === 'no' || isset($_GET['reset_access'])) {
            unset($_SESSION['student_access']);
            redirect('student?notice=info_hidden', false, false);
            return;
        }

        $access_granted = isset($_SESSION['student_access'])
            && $_SESSION['student_access'] === 'student-portal-cleared';

        $this->call->view('student_home', [
            'student' => $this->student(),
            'access_granted' => $access_granted,
            'notice' => $_GET['notice'] ?? ''
        ]);
    }

    public function profile()
    {
        $this->call->view('student_profile', [
            'student' => $this->student()
        ]);
    }
}
