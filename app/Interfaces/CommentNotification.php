<?php


namespace App\Interfaces;


interface CommentNotification
{
    public function getCommentNotificationMessage() : string;
}