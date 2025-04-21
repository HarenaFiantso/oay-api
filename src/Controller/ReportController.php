<?php

namespace App\Controller;

use App\Manager\ReportManager;
use App\Repository\ReportRepository;
use App\Repository\UserRepository;
use App\Repository\VotingRepository;

class ReportController extends AbstractBaseController
{
    public const CORRECT = 'true';
    public const INCORRECT = 'false';
    public const POINT = 1;
    public const HAHA = 'haha';

    private mixed $filePath;
    private UserRepository $userRepos;
    private ReportManager $reportManager;
    private VoteManager $voteManager;
    private VotingRepository $votingRepository;
    private ReportRepository $reportRepository;
}