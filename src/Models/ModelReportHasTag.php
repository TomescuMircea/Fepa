<?php

namespace Src\Models;

class ModelReportHasTag
{
    private $reportId;
    private $tagId;

    public function __construct()
    {
    }

    public function getReportId()
    {
        return $this->reportId;
    }

    public function setReportId($reportId)
    {
        $this->reportId = $reportId;
    }

    public function getTagId()
    {
        return $this->tagId;
    }

    public function setTagId($tagId)
    {
        $this->tagId = $tagId;
    }
}