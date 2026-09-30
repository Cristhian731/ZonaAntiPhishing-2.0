<?php

require_once __DIR__ . '/../models/Lesson.php';

class LessonController
{
    private Lesson $lessonModel;

    public function __construct()
    {
        $this->lessonModel = new Lesson();
    }

    public function getLessonsByCourse(int $courseId)
    {
        return $this->lessonModel->getLessonsByCourseId($courseId);
    }

    public function getLesson(int $id)
    {
        return $this->lessonModel->getLessonById($id);
    }
}
