<?php

require_once __DIR__ . '/../models/Course.php';

class CourseController
{
    private Course $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
    }

    public function getCourses()
    {
        return $this->courseModel->getAllCourses();
    }

    public function getCourse(int $id)
    {
        return $this->courseModel->getCourseById($id);
    }
}
