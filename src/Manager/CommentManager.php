<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Report;
use App\Utils\HtmlToEmoji;

class CommentManager extends AbstractManager
{
    public function formatCommentsForReport(Report $report): array
    {
        $formattedComments = [];

        foreach ($report->getComments()->getValues() as $key => $comment) {
            $user = $comment->getUser();

            $formattedComments[$key] = [
                'id' => $comment->getId(),
                'content' => HtmlToEmoji::convertTextToEmoji($comment->getContent()),
                'responses' => $this->formatReplies($comment),
                'author' => [
                    'id' => $user?->getId(),
                    'name' => $user?->getFullName() ?? 'Oay',
                    'gender' => $user?->getGender() ?? 'Man',
                ],
                'createdAt' => $comment->getDateAdd()->format('d-m-Y H:i'),
            ];
        }

        return array_reverse($formattedComments);
    }

    public function formatReplies(Comment $comment): array
    {
        $formattedReplies = [];

        foreach ($comment->getChildComments()->getValues() as $key => $reply) {
            $user = $reply->getUser();

            $formattedReplies[$key] = [
                'id' => $reply->getId(),
                'content' => HtmlToEmoji::convertTextToEmoji($reply->getContent()),
                'author' => [
                    'id' => $user?->getId(),
                    'fullName' => $user?->getFullName() ?? 'Oay',
                    'gender' => $user?->getGender() ?? 'Man',
                ],
                'createdAt' => $reply->getCreatedAt()->format('d-m-Y H:i'),
            ];
        }

        return $formattedReplies;
    }
}
