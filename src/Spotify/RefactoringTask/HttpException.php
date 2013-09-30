<?php

namespace Spotify\RefactoringTask;

class HttpException extends \Exception
{
    const ERROR_URL_NOT_SET = 1001;
    const ERROR_REQUEST_WITHOUT_DATA = 1002;
}