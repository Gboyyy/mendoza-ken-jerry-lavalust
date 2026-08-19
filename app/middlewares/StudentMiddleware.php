<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentMiddleware
{
    public function handle(Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $has_access = isset($_SESSION['student_access'])
            && $_SESSION['student_access'] === 'student-portal-cleared';

        if (! $has_access) {
            redirect('student?notice=profile_guarded', false, false);
            return null;
        }

        return $next();
    }
}
